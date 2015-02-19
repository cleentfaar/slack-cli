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
use Symfony\Component\Console\Input\InputArgument;
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
     * @return ChannelsCreatePayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsCreatePayload();
        $payload->setName($this->input->getArgument('name'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsCreatePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully created channel!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $channelData = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($channelData);
            }
        } else {
            $this->writeError(sprintf('Failed to create channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
