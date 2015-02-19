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

use CL\Slack\Payload\ChannelsRenamePayload;
use CL\Slack\Payload\ChannelsRenamePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsRenameCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:rename');
        $this->setDescription('Leave a channel (as the user of the token).');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to rename');
        $this->addArgument('name', InputArgument::REQUIRED, 'The new name for this channel');
        $this->setHelp(<<<EOT
The <info>channels:rename</info> command renames a team channel.

The only people who can rename a channel are team admins, or the person that originally created the channel.
Others will receive a "not_authorized" error.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.rename</comment>
EOT
        );
    }

    /**
     * @return ChannelsRenamePayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsRenamePayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setName($this->input->getArgument('name'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsRenamePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully renamed channel!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $this->output->writeln('Renamed channel:');
                $data = $this->serializeObjectToArray($payloadResponse->getChannel());
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to rename channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
