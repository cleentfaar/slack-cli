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

use CL\Slack\Payload\GroupsKickPayload;
use CL\Slack\Payload\GroupsKickPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsKickCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:kick');
        $this->setDescription('Removes (kicks) a given user from a group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to remove the user from');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to remove');
        $this->setHelp(<<<EOT
The <info>groups:kick</info> command allows you to remove another member from a grouo.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.kick</comment>
EOT
        );
    }

    /**
     * @return GroupsKickPayload
     */
    protected function createPayload()
    {
        $payload = new GroupsKickPayload();
        $payload->setGroupId($this->input->getArgument('group-id'));
        $payload->setUserId($this->input->getArgument('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsKickPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully kicked user from the group!');
        } else {
            $this->writeError(sprintf('Failed to kick user from the group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
