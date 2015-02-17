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

use CL\Slack\Payload\ChannelsHistoryPayload;
use CL\Slack\Payload\ChannelsHistoryPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsHistoryCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('channels:history');
        $this->setDescription('Returns a portion of messages/events from the specified channel (see `--help`)');
        $this->addArgument('channel-id', InputArgument::REQUIRED, 'Channel to fetch history for');
        $this->addOption('latest', 'l', InputOption::VALUE_REQUIRED, 'Latest message timestamp to include in results');
        $this->addOption('oldest', 'o', InputOption::VALUE_REQUIRED, 'Oldest message timestamp to include in results');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of messages to return, between 1 and 1000.');
        $this->setHelp(<<<EOT
The <info>channels:history</info> command returns a portion of messages/events from the specified channel.
To read the entire history for a channel, run the command with no `latest` or `oldest` options, and then continue paging
using the instructions below.

The messages array up to 100 messages between `--latest` and `--oldest`. If there were more than 100 messages between
those two points, then has_more will be true.

If a message has the same timestamp as latest or oldest it will not be included in the list. This allows a client to fetch
all messages in a hole in channel history, by running the <info>channels.history</info> command with `--latest`
set to the oldest message they have after the hole, and `--oldest` to the latest message they have before the hole.
If the response includes `has_more` then the client can make another call, using the `ts` value of the final messages as
the latest param to get the next page of messages.

If there are more than 100 messages between the two timestamps then the messages returned are the ones closest to latest.
In most cases an application will want the most recent messages and will page backward from there. If oldest is provided
but not latest then the messages returned are those closest to oldest, allowing you to page forward through history if desired.

If either of the latest or oldest arguments are provided then those timestamps will also be included in the output.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/channels.history</comment>
EOT
        );
    }

    /**
     * @return ChannelsHistoryPayload
     */
    protected function createPayload()
    {
        $payload = new ChannelsHistoryPayload();
        $payload->setChannelId($this->input->getArgument('channel-id'));
        $payload->setLatest($this->input->getOption('latest'));
        $payload->setOldest($this->input->getOption('oldest'));
        $payload->setCount($this->input->getOption('count'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChannelsHistoryPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully retrieved history');
            $this->renderTable($payloadResponse->getMessages());
            if ($payloadResponse->getLatest() !== null) {
                $this->output->writeln(sprintf('Latest: <comment>%s</comment>', $payloadResponse->getLatest()));
            }
            if ($payloadResponse->getHasMore() !== null) {
                $this->output->writeln(sprintf('Has more: <comment>%s</comment>', $payloadResponse->getHasMore() ? 'yes' : 'no'));
            }
        } else {
            $this->writeError(sprintf('Failed to retrieve history for this channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
