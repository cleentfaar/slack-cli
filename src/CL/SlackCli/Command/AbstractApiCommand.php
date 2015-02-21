<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Command;

use CL\Slack\Exception\SlackException;
use CL\Slack\Model\AbstractModel;
use CL\Slack\Model\Customizable;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use CL\Slack\Test\Transport\MockApiClient;
use CL\Slack\Transport\ApiClient;
use CL\Slack\Transport\Events\RequestEvent;
use CL\Slack\Transport\Events\ResponseEvent;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractApiCommand extends AbstractCommand
{
    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'token',
            't',
            InputOption::VALUE_REQUIRED,
            'Token to use during the API-call (defaults to the one defined in the global configuration)'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $payload = $this->createPayload();

        if (!($payload instanceof PayloadInterface)) {
            throw new \RuntimeException(sprintf(
                '%s::createPayload() should return an object implementing %s, got: %s',
                get_class($this),
                'CL\Slack\Payload\PayloadInterface',
                var_export($payload, true)
            ));
        }

        $response   = $this->sendPayload($payload);
        $returnCode = $this->handleResponse($response);

        if (null === $returnCode) {
            return $response->isOk() ? 0 : 1;
        }

        return (int) $returnCode;
    }

    /**
     * @param PayloadInterface $payload
     *
     * @return PayloadResponseInterface
     *
     * @throws SlackException
     */
    private function sendPayload(PayloadInterface $payload)
    {
        if ($this->input->getOption('token')) {
            $token = $this->input->getOption('token');
        } else {
            $token = $this->config->get('default_token');
        }

        if (empty($token)) {
            throw new \RuntimeException(
                'No token provided by `--token` option and no value for `default_token` was found '.
                'in the global configuration. Use the `--token` option or set the token globally '.
                'by running `slack.phar config.set default_token your-token-here`'
            );
        }
        
        if ($this->isTest()) {
            $apiClient = new MockApiClient();
            
            return $apiClient->send($payload, $token, $this->isTestSuccess());
        }
         
        $apiClient = new ApiClient($token);
        
        $this->configureListeners($apiClient);
        
        return $apiClient->send($payload, $token);
    }

    /**
     * @param array|object|null $data
     * @param array             $headers
     */
    protected function renderKeyValueTable($data, array $headers = [])
    {
        if ($data === null) {
            return;
        }

        if (is_object($data)) {
            $data = $this->serializeObjectToArray($data);
        }

        $rows = [];
        foreach ($data as $key => $value) {
            $rows[] = [$key, $value];
        }

        $this->renderTable($rows, $headers);
    }

    /**
     * @param array      $rows
     * @param array|null $headers
     */
    protected function renderTable(array $rows, $headers = null)
    {
        $table = new Table($this->output);

        if (!empty($headers)) {
            $table->setHeaders($headers);
        } elseif ($headers === null && !empty($rows)) {
            $firstRow = reset($rows);
            if ($firstRow instanceof AbstractModel || $firstRow instanceof PayloadResponseInterface) {
                $firstRow = $this->serializeObjectToArray($firstRow);
            }

            $table->setHeaders(array_keys($firstRow));
        }

        $table->setRows($this->simplifyRows($rows));

        $style = Table::getStyleDefinition('default');
        $style->setCellRowFormat('<fg=yellow>%s</>');
        $table->render();
    }

    /**
     * @param array $rows
     *
     * @return array
     */
    protected function simplifyRows(array $rows)
    {
        $simplified = [];
        foreach ($rows as $x => $row) {
            $row = $this->formatTableRow($row);
            foreach ($row as $column => $value) {
                if (!is_scalar($value)) {
                    $value = $this->formatNonScalarColumnValue($value);
                } elseif (is_bool($value)) {
                    $value = $value ? 'true' : 'false';
                }

                $simplified[$x][$column] = $value;
            }
        }

        return $simplified;
    }

    /**
     * @param object|array $row
     *
     * @return array
     */
    protected function formatTableRow($row)
    {
        if (is_object($row)) {
            $row = $this->serializeObjectToArray($row);
        }

        return $row;
    }

    /**
     * @param $nonScalar
     *
     * @return string
     */
    protected function formatNonScalarColumnValue($nonScalar)
    {
        if ($nonScalar instanceof Customizable) {
            $nonScalar = $nonScalar->getValue();
        } else {
            $nonScalar = json_encode($nonScalar);
        }

        return $nonScalar;
    }

    /**
     * @param object|null $object
     *
     * @return array
     */
    protected function serializeObjectToArray($object)
    {
        if ($object === null) {
            return [];
        }

        $data = $this->getSerializer()->serialize($object, 'json');

        if (!empty($data)) {
            return json_decode($data, true);
        }

        return [];
    }

    /**
     * @param ApiClient $apiClient
     */
    private function configureListeners(ApiClient $apiClient)
    {
        $output = $this->output;
        if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
            $self = $this;

            $apiClient->addRequestListener(function (RequestEvent $event) use ($output, $self) {
                $rawRequest = $event->getRawPayload();
                $output->writeln('<comment>Debug: sending payload...</comment>');
                $self->renderKeyValueTable($output, $rawRequest);
            });

            $apiClient->addResponseListener(function (ResponseEvent $event) use ($output, $self) {
                $rawResponse = $event->getRawPayloadResponse();
                $output->writeln('<comment>Debug: received payload response...</comment>');
                $self->renderKeyValueTable($output, $rawResponse);
            });
        }
    }

    /**
     * @return SerializerInterface
     */
    private function getSerializer()
    {
        if (!isset($this->payloadSerializer)) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }

    /**
     * @return PayloadInterface
     */
    abstract protected function createPayload();

    /**
     * @param PayloadResponseInterface $payloadResponse
     *
     * @return int|null
     */
    abstract protected function handleResponse($payloadResponse);
}
