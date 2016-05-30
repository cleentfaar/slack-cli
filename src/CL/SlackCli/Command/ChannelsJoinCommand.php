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

use CL\Slack\Payload\ChannelsJoinPayload;
use CL\Slack\Payload\ChannelsJoinPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsJoinCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:join');
        $this->setDescription('Joins a channel with the token\'s user (creates channel if it doesn\'t exist)');
        $this->addArgument('channel', InputArgument::REQUIRED, 'The name of the channel to join (or create if it doesn\'t exist yet)');
        $this->setHelp(<<<EOT
The <info>channels.join</info> command is used to join a channel.
If the channel does not exist, it is created.

Unlike the other channels-commands, this command requires you to supply the NAME instead of the ID of the channel,
because the channel might be created if it doesn't exist yet.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.join</comment>
EOT
        );
    }

    /**
     * @return ChannelsJoinPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsJoinPayload();
        $payload->setName($this->input->getArgument('channel'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsJoinPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                if ($payloadResponse->isAlreadyInChannel()) {
                    $this->writeError('You are already in this channel:');
                } else {
                    $this->writeOk('Successfully joined channel:');
                }

                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to join channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
