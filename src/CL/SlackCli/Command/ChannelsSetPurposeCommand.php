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

use CL\Slack\Payload\ChannelsSetPurposePayload;
use CL\Slack\Payload\ChannelsSetPurposePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsSetPurposeCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:set-purpose');
        $this->setDescription('Change the purpose of a channel. The calling user must be a member of the channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to change the purpose of');
        $this->addArgument('purpose', InputArgument::REQUIRED, 'The new purpose');
        $this->setHelp(<<<EOT
The <info>channels:set-purpose</info> command changes the purpose of a channel.
The calling user must be a member of the channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.setPurpose</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return ChannelsSetPurposePayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ChannelsSetPurposePayload();
        $payload->setChannelId($input->getArgument('channel-id'));
        $payload->setPurpose($input->getArgument('purpose'));
        
        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsSetPurposePayloadResponse $payloadResponse
     * @param InputInterface                    $input
     * @param OutputInterface                   $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, sprintf('Successfully changed purpose of channel to: "%s"', $payloadResponse->getPurpose()));
        } else {
            $this->writeError($output, sprintf('Failed to change purpose of channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
