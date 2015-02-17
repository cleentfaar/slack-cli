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

use CL\Slack\Payload\ChannelsInvitePayloadResponse;
use CL\Slack\Payload\ChannelsKickPayload;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsKickCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:kick');
        $this->setDescription('Removes (kicks) a given user from a given channel');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to remove');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to remove the user from');
        $this->setHelp(<<<EOT
The <info>channels.kick</info> command allows you to remove a given user from a given channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.kick</comment>
EOT
        );
    }

    /**
     * @return ChannelsKickPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsKickPayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setUserId($this->input->getArgument('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInvitePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully kicked user from the channel!');
        } else {
            $this->writeError(sprintf('Failed to kick user from the channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
