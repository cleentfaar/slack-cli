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

use CL\Slack\Payload\GroupsMarkPayload;
use CL\Slack\Payload\GroupsMarkPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsMarkCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:mark');
        $this->setDescription('Moves the read cursor in a Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'ID of the group to set reading cursor in.');
        $this->addArgument('timestamp', InputArgument::REQUIRED, 'Timestamp of the most recently seen message.');
        $this->setHelp(<<<EOT
The <info>groups.mark</info> command is used to move the read cursor in a Slack group.

After running this command, the mark is saved to the database and broadcast via the message server to all open connections
for the token's user.

You should try to avoid running this command too often. When needing to mark a read position, you should set a timer
before running the command. In this way, any further updates needed during the timeout will not generate extra calls
(just one per channel).

This is useful for when reading scroll-back history, or following a busy live channel. A timeout of 5 seconds is a good
starting point. Be sure to flush these calls on shutdown/logout.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.mark</comment>
EOT
        );
    }

    /**
     * @return GroupsMarkPayload
     */
    protected function createPayload()
    {
        $payload = new GroupsMarkPayload();
        $payload->setGroupId($this->input->getArgument('group-id'));
        $payload->setSlackTimestamp($this->input->getArgument('timestamp'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsMarkPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully moved the read cursor!');
        } else {
            $this->writeError(sprintf('Failed to move the read cursor in the group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
