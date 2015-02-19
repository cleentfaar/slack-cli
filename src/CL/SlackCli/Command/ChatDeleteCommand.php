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

use CL\Slack\Payload\ChatDeletePayload;
use CL\Slack\Payload\ChatDeletePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChatDeleteCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('chat:delete');
        $this->setDescription('Deletes a message from a given channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel containing the message to be deleted');
        $this->addArgument('timestamp', InputArgument::REQUIRED, 'Timestamp of the message to be deleted');
        $this->setHelp(<<<EOT
The <info>chat:delete</info> command deletes a message from a given channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/chat.delete</comment>
EOT
        );
    }

    /**
     * @return ChatDeletePayload
     */
    protected function createPayload()
    {
        $payload = new ChatDeletePayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setSlackTimestamp($this->input->getArgument('timestamp'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChatDeletePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully deleted message!');
        } else {
            $this->writeError(sprintf('Failed to delete message: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
