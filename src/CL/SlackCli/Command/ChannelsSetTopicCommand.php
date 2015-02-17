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

use CL\Slack\Payload\ChannelsSetTopicPayload;
use CL\Slack\Payload\ChannelsSetTopicPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsSetTopicCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:set-topic');
        $this->setDescription('Change the topic of a channel. The calling user must be a member of the channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to change the topic of');
        $this->addArgument('topic', InputArgument::REQUIRED, 'The new topic');
        $this->setHelp(<<<EOT
The <info>channels.setTopic</info> command changes the topic of a channel.
The calling user must be a member of the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.setTopic</comment>
EOT
        );
    }

    /**
     * @return ChannelsSetTopicPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsSetTopicPayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setTopic($this->input->getArgument('topic'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsSetTopicPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk(sprintf('Successfully changed topic of channel to: "%s"', $payloadResponse->getTopic()));
        } else {
            $this->writeError(sprintf('Failed to change topic of channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
