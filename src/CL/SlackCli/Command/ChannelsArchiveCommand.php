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

use CL\Slack\Payload\ChannelsArchivePayload;
use CL\Slack\Payload\ChannelsArchivePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsArchiveCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:archive');
        $this->setDescription('Archives a given Slack channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to archive');
        $this->setHelp(<<<EOT
The <info>channels:archive</info> command let's you archive a given Slack channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.archive</comment>
EOT
        );
    }

    /**
     * @return ChannelsArchivePayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsArchivePayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsArchivePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully archived channel!');
        } else {
            $this->writeError(sprintf('Failed to archive channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
