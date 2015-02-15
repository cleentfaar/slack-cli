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
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param InputInterface $input
     *
     * @return ChannelsKickPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ChannelsKickPayload();
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setUserId($input->getArgument('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInvitePayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully kicked user from the channel!');
        } else {
            $this->writeError($output, sprintf('Failed to kick user from the channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
