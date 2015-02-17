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

use CL\Slack\Payload\ChannelsLeavePayload;
use CL\Slack\Payload\ChannelsLeavePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsLeaveCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:leave');
        $this->setDescription('Leave a channel (as the user of the token).');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to leav');
        $this->setHelp(<<<EOT
The <info>channels:leave</info> command leaves a channel as the user of the token.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.leave</comment>
EOT
        );
    }

    /**
     * @return ChannelsLeavePayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsLeavePayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsLeavePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isNotInChannel()) {
                $this->writeError('Could not leave channel; not in channel');
            } else {
                $this->writeOk('Successfully left channel!');
            }
        } else {
            $this->writeError(sprintf('Failed to leave channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
