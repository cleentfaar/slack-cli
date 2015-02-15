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

use CL\Slack\Payload\ApiTestPayload;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\ApiTestPayloadResponse;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ApiTestCommand extends AbstractApiCommand
{
    /**
     * @var bool
     */
    private $expectError = false;

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('api:test');
        $this->setDescription('Tests connecting with the Slack API using the token.');
        $this->addOption('arguments', null, InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'Arguments to be tested in key:value format, such as "foo:bar"');
        $this->addOption('error', null, InputOption::VALUE_REQUIRED, 'Optional error message to mock an error response from Slack with');
        $this->setHelp(<<<EOT
The <info>api:test</info> command lets you connect with the Slack API for testing purposes.

Testing arguments returned by Slack
<info>php bin/slack api.test --args="foo=bar&apple=pie"</info>

Testing an error response
<info>php bin/slack api.test --error="This is my error"</info>

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/api.test</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return ApiTestPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ApiTestPayload();
        
        if ($input->getOption('arguments')) {
            $args = [];
            foreach ($input->getOption('arguments') as $keyValue) {
                list($key, $value) = explode(':', $keyValue);
                $args[$key] = $value;
            }
            
            $payload->replaceArguments($args);
        }

        if ($input->getOption('error')) {
            $this->expectError = true;
            $payload->setError($input->getOption('error'));
        }
        
        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ApiTestPayloadResponse $payloadResponse
     * @param InputInterface         $input
     * @param OutputInterface        $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($this->expectError === true || $payloadResponse->isOk()) {
            $this->writeOk($output, 'Slack API seems to have responded correctly (no error expected, no error returned)');
            $data = [];
            if ($payloadResponse->getError()) {
                $data['error'] = $payloadResponse->getError();
            }
            foreach ($payloadResponse->getArguments() as $key => $val) {
                if ($key == 'token') {
                    continue;
                }

                $data['args'][$key] = $val;
            }
            $this->renderKeyValueTable($output, $data);
            
            // force 0 so any error tested here won't trigger a failure
            return 0;
        } else {
            $this->writeError($output, sprintf(
                'Slack API did not respond correctly (no error expected): %s',
                lcfirst($payloadResponse->getErrorExplanation())
            ));
            return 1;
        }
    }
}
