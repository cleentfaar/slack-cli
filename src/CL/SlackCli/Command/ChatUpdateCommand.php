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

use CL\Slack\Payload\ChatUpdatePayload;
use CL\Slack\Payload\ChatUpdatePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChatUpdateCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('chat:update');
        $this->setDescription('Updates a message from a given channel');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel containing the message to be updated');
        $this->addArgument('timestamp', InputArgument::REQUIRED, 'Timestamp of the message to be updated');
        $this->addArgument('text', InputArgument::REQUIRED, 'New text for the message, using the default formatting rules');
        $this->setHelp(<<<EOT
The <info>chat:update</info> command updates a message from a given channel.

The new message uses the default formatting rules, which can be found here: <comment>https://api.slack.com/docs/formatting</comment>

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/chat.update</comment>
EOT
        );
    }

    /**
     * @return ChatUpdatePayload
     */
    protected function createPayload()
    {
        $payload = new ChatUpdatePayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setSlackTimestamp($this->input->getArgument('timestamp'));
        $payload->setText($this->input->getArgument('text'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChatUpdatePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully updated message!');
        } else {
            $this->writeError(sprintf('Failed to update message: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
