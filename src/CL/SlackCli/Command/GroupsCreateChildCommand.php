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

use CL\Slack\Payload\GroupsCreateChildPayload;
use CL\Slack\Payload\GroupsCreateChildPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsCreateChildCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:create-child');
        $this->setDescription('This method creates a child group from an existing group (see `--help`)');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The name of the channel to create (must not exist already)');
        $this->setHelp(<<<EOT
The <info>groups:create-child</info> command takes an existing private group and performs the following steps:

- Renames the existing group (from "example" to "example-archived").
- Archives the existing group.
- Creates a new group with the name of the existing group.
- Adds all members of the existing group to the new group.

This is useful when inviting a new member to an existing group while hiding all previous chat history from them.
In this scenario you can run <info>groups.createChild</info> followed by <info>groups.invite</info>.

The new group will have a special `parent_group` property pointing to the original archived group.
This will only be returned for members of both groups, so will not be visible to any newly invited members.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.createChild</comment>
EOT
        );
    }

    /**
     * @return GroupsCreateChildPayload
     */
    protected function createPayload()
    {
        $payload = new GroupsCreateChildPayload();
        $payload->setGroupId($this->input->getArgument('group-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreateChildPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully created child group!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $data = $this->serializeObjectToArray($payloadResponse->getGroup());
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to create child group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
