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

use CL\Slack\Payload\ChannelsInvitePayload;
use CL\Slack\Payload\ChannelsInvitePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsInviteCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:invite');
        $this->setDescription('Invites a user to a channel. The calling user must be a member of the channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to invite the user to');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to invite');
        $this->setHelp(<<<EOT
The <info>channels.invite</info> command is used to invite a user to a channel.
The calling user must be a member of the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.invite</comment>
EOT
        );
    }

    /**
     * @return ChannelsInvitePayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsInvitePayload();
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
            $this->writeOk('Successfully invited user to the channel!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $this->output->writeln('Channel used:');
                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to invite user to this channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
