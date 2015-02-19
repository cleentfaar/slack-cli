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

use CL\Slack\Payload\UsersListPayload;
use CL\Slack\Payload\UsersListPayloadResponse;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class UsersListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('users:list');
        $this->setDescription('Returns a list of all users in the team (including deleted/deactivated users)');
        $this->setHelp(<<<EOT
The <info>users:list</info> command returns a list of all users in the team. This includes deleted/deactivated users.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/users.list</comment>
EOT
        );
    }

    /**
     * @return UsersListPayload
     */
    protected function createPayload()
    {
        $payload = new UsersListPayload();

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersListPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $users = $payloadResponse->getUsers();
            $this->output->writeln(sprintf('Received <comment>%d</comment> users...', count($users)));
            if (!empty($users)) {
                $this->renderTable($users, null);
                $this->writeOk('Successfully listed users');
            } else {
                $this->writeError('No users seem to be assigned to your team... this is strange...');
            }
        } else {
            $this->writeError(sprintf('Failed to list users: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
