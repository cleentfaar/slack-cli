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

use CL\Slack\Payload\ChannelsInfoPayload;
use CL\Slack\Payload\ChannelsInfoPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsInfoCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:info');
        $this->setDescription('Returns information about a team channel.');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'The ID of the channel to get information on');
        $this->setHelp(<<<EOT
The <info>channels:info</info> command returns information about a given channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.info</comment>
EOT
        );
    }

    /**
     * @return ChannelsInfoPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsInfoPayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsInfoPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $data = $this->serializeObjectToArray($payloadResponse->getChannel());
            $this->renderKeyValueTable($data);
            $this->writeOk('Successfully retrieved information about the channel!');
        } else {
            $this->writeError(sprintf('Failed to retrieve information about the channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
