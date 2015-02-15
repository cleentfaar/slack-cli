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

use CL\Slack\Payload\ChatPostMessagePayload;
use CL\Slack\Payload\ChatPostMessagePayloadResponse;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChatPostMessageCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('chat:post-message');
        $this->setAliases(['chat.postMessage']);
        $this->setDescription('Sends a message to a Slack channel of your choice');
        $this->addArgument('channel', InputArgument::REQUIRED, 'The channel to send the message to');
        $this->addArgument('text', InputArgument::REQUIRED, 'The text of the message to send');
        $this->addOption('username', 'u', InputOption::VALUE_REQUIRED, 'The username that will send this text (does not have to exist in your Team)');
        $this->addOption('icon-emoji', 'ie', InputOption::VALUE_REQUIRED, 'Emoji to use as the icon for this message. Overrides `--icon_url`.');
        $this->addOption('icon-url', 'iu', InputOption::VALUE_REQUIRED, 'URL to an image to use as the icon for this message');
        $this->addOption('parse', 'p', InputOption::VALUE_REQUIRED, 'Change how messages are treated. See information in `--help`');
        $this->addOption('link-names', 'l', InputOption::VALUE_REQUIRED, 'Set this flag to `true` to automatically link channel-names and usernames');
        $this->addOption('unfurl-links', 'ul', InputOption::VALUE_REQUIRED, 'Pass true to enable unfurling of primarily text-based content');
        $this->addOption('unfurl-media', 'um', InputOption::VALUE_REQUIRED, 'Pass false to disable unfurling of media content');
        $this->setHelp(<<<EOT
The <info>chat:post-message</info> command posts a message to a given channel.

Messages are formatted as described in the formatting spec (link below). You can specify values for `parse` and `link_names`
to change formatting behavior.

The optional attachments argument should contain a JSON-encoded array of attachments. For more information, see the
`attachments` spec (link below).

By default links to media are unfurled, but links to text content are not.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/chat.postMessage</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return ChatPostMessagePayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ChatPostMessagePayload();
        $channel = $input->getArgument('channel');

        // help support un-escaped channel names such as 'general' (the hash-sign requires the channel name to be quoted)
        // also making sure to ignore it if a channel ID was given
        if (substr($channel, 0, 1) !== '#' && !is_numeric(substr($channel, 1))) {
            $channel = '#'.$channel;
        }

        $payload->setChannel($channel);
        $payload->setText($input->getArgument('text'));

        if ($input->getOption('username')) {
            $payload->setUsername($input->getOption('username'));
        }

        if ($input->getOption('icon-url')) {
            $payload->setIconUrl($input->getOption('icon-url'));
        }

        if ($input->getOption('icon-emoji')) {
            $payload->setIconEmoji($input->getOption('icon-emoji'));
        }

        if ($input->getOption('parse')) {
            $payload->setParse($input->getOption('parse'));
        }

        if ($input->getOption('link-names')) {
            $payload->setLinkNames($input->getOption('link-names'));
        }

        if ($input->getOption('unfurl-links')) {
            $payload->setUnfurlLinks($input->getOption('unfurl-links'));
        }

        if ($input->getOption('unfurl-media')) {
            $payload->setUnfurlMedia($input->getOption('unfurl-media'));
        }

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ChatPostMessagePayloadResponse $payloadResponse
     * @param InputInterface                 $input
     * @param OutputInterface                $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully sent message to Slack!');
            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $output->writeln(sprintf('Channel ID: <comment>%s</comment>', $payloadResponse->getChannel()));
                $output->writeln(sprintf('Timestamp: <comment>%s</comment>', $payloadResponse->getTimestamp()));
            }
        } else {
            $this->writeError($output, sprintf('Failed to send message to Slack: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
