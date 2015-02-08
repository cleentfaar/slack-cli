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

use CL\Slack\Model\AbstractModel;
use CL\Slack\Model\Customizable;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use CL\Slack\Transport\ApiClient;
use CL\Slack\Transport\Events\RequestEvent;
use CL\Slack\Transport\Events\ResponseEvent;
use CL\Slack\Util\PayloadRegistry;
use CL\Slack\Util\PayloadSerializer;
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
     * @var ApiClient|null
     */
    private $apiClient;

    /**
     * @var PayloadRegistry
     */
    private $payloadRegistry;

    /**
     * @var SerializerInterface|null
     */
    private $serializer;

    /**
     * @var array
     */
    private $rawRequest = [];

    /**
     * @var array
     */
    private $rawResponse = [];

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
        $apiClient = $this->getApiClient();
        $payload   = $this->getPayloadRegistry()->get($this->getName());

        $this->configureListeners($apiClient, $output);
        $this->configurePayload($payload, $input);

        if ($input->getOption('token')) {
            $token = $input->getOption('token');
        } else {
            $token = $this->config->get('default_token');
        }

        if (empty($token)) {
            throw new \RuntimeException(
                'No token provided by `--token` option and no value for `default_token` was found ' .
                'in the global configuration. Use the `--token` option or set the token globally ' .
                'by running `slack.phar config.set default_token your-token-here`'
            );
        }

        $response   = $apiClient->send($payload, $token);
        $returnCode = $this->handleResponse($response, $input, $output);

        if (null === $returnCode) {
            return 0;
        }

        return (int) $returnCode;
    }

    /**
     * @return ApiClient
     */
    protected function getApiClient()
    {
        if (!isset($this->apiClient)) {
            $payloadSerializer = new PayloadSerializer(SerializerBuilder::create()->build());
            $apiClient         = new ApiClient(null, $payloadSerializer);

            $this->apiClient = $apiClient;
        }

        return $this->apiClient;
    }

    /**
     * @return PayloadRegistry
     */
    protected function getPayloadRegistry()
    {
        if (!isset($this->payloadRegistry)) {
            $this->payloadRegistry = new PayloadRegistry();
        }

        return $this->payloadRegistry;
    }

    /**
     * @param OutputInterface $output
     */
    protected function renderPayloadResponse(OutputInterface $output)
    {
        $this->renderKeyValueTable($output, $this->rawResponse);
    }

    /**
     * @param OutputInterface $output
     * @param array|object    $data
     * @param array           $headers
     */
    protected function renderKeyValueTable(OutputInterface $output, $data, array $headers = [])
    {
        if (is_object($data)) {
            $data = $this->serializeObjectToArray($data);
        }

        $rows = [];
        foreach ($data as $key => $value) {
            $rows[] = [$key, $value];
        }

        $this->renderTable($output, $rows, $headers);
    }

    /**
     * @param OutputInterface $output
     * @param array           $rows
     * @param array|null      $headers
     */
    protected function renderTable(OutputInterface $output, array $rows, $headers = null)
    {
        $table = new Table($output);

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
     * @param object $object
     *
     * @return array
     */
    protected function serializeObjectToArray($object)
    {
        $json = $this->getSerializer()->serialize($object, 'json');
        $data = json_decode($json, true);

        if (!empty($data)) {
            return $data;
        }

        return [];
    }

    /**
     * @param ApiClient       $apiClient
     * @param OutputInterface $output
     */
    private function configureListeners(ApiClient $apiClient, OutputInterface $output)
    {
        $self = $this;

        $apiClient->addListener(
            ApiClient::EVENT_REQUEST,
            function (RequestEvent $event) use ($output, $self) {
                $self->rawRequest = $event->getRawPayload();
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln('<comment>Debug: sending payload...</comment>');
                    $this->renderKeyValueTable($output, $self->rawRequest);
                }
            }
        );

        $apiClient->addListener(
            ApiClient::EVENT_RESPONSE,
            function (ResponseEvent $event) use ($output, $self) {
                $self->rawResponse = $event->getRawPayloadResponse();
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
                    $output->writeln('<comment>Debug: received payload response...</comment>');
                    $this->renderKeyValueTable($output, $self->rawResponse);
                }
            }
        );
    }

    /**
     * @return SerializerInterface
     */
    private function getSerializer()
    {
        if (!isset($this->serializer)) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }

    /**
     * @param PayloadResponseInterface $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     *
     * @return int
     */
    abstract protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output);

    /**
     * @param PayloadInterface $payload
     * @param InputInterface   $input
     */
    abstract protected function configurePayload(PayloadInterface $payload, InputInterface $input);
}
