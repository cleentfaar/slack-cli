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

use CL\Slack\Payload\GroupsRenamePayload;
use CL\Slack\Payload\GroupsRenamePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsRenameCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:rename');
        $this->setDescription('Leave a group (as the user of the token).');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to rename');
        $this->addArgument('name', InputArgument::REQUIRED, 'The new name for this group');
        $this->setHelp(<<<EOT
The <info>groups:rename</info> command renames a team group.

The only people who can rename a group are team admins, or the person that originally created the group.
Others will receive a "not_authorized" error.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.rename</comment>
EOT
        );
    }

    /**
     * @return GroupsRenamePayload
     */
    protected function createPayload()
    {
        $payload = new GroupsRenamePayload();
        $payload->setGroupId($this->input->getArgument('group-id'));
        $payload->setName($this->input->getArgument('name'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsRenamePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully renamed group!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $this->output->writeln('Renamed group:');
                $data = $this->serializeObjectToArray($payloadResponse->getGroup());
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to leave group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
