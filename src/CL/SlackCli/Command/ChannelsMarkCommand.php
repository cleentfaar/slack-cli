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

use CL\Slack\Payload\ChannelsMarkPayload;
use CL\Slack\Payload\ChannelsMarkPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsMarkCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:mark');
        $this->setDescription('Moves the read cursor in a Slack channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'ID of the channel to set reading cursor in.');
        $this->addArgument('timestamp', InputArgument::REQUIRED, 'Timestamp of the most recently seen message.');
        $this->setHelp(<<<EOT
The <info>channels:mark</info> command is used to move the read cursor in a Slack channel.

After making this call, the mark is saved to the database and broadcast via the message server to all open connections
for the calling user.

Clients should try to avoid making this call too often. When needing to mark a read position, a client should set a
timer before making the call. In this way, any further updates needed during the timeout will not generate extra calls
(just one per channel). This is useful for when reading scroll-back history, or following a busy live channel.

A timeout of 5 seconds is a good starting point. Be sure to flush these calls on shutdown/logout.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.mark</comment>
EOT
        );
    }

    /**
     * @return ChannelsMarkPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsMarkPayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsMarkPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully moved the read cursor!');
        } else {
            $this->writeError(sprintf('Failed to move the read cursor in the channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
