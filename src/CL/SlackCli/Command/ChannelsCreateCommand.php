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

use CL\Slack\Payload\ChannelsCreatePayload;
use CL\Slack\Payload\ChannelsCreatePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsCreateCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:create');
        $this->setDescription('Creates new Slack channel with the given name');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the channel to create (must not exist already)');
        $this->setHelp(<<<EOT
The <info>channels:create</info> command let's you create a new Slack channel with the given name.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.create</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return ChannelsCreatePayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ChannelsCreatePayload();
        $payload->setName($input->getArgument('name'));
        
        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsCreatePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully created channel!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $channelData = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($output, $channelData);
            }
        } else {
            $this->writeError($output, sprintf('Failed to create channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
