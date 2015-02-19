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

use CL\Slack\Payload\GroupsListPayload;
use CL\Slack\Payload\GroupsListPayloadResponse;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:list');
        $this->setDescription('Returns a list of all groups in your Slack team');
        $this->addOption('exclude-archived', null, InputOption::VALUE_OPTIONAL, 'Don\'t return archived groups.');
        $this->setHelp(<<<EOT
This method returns a list of groups in the team that the caller is in and archived groups that the caller was in.
The list of (non-deactivated) members in each group is also returned.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.list</comment>
EOT
        );
    }

    /**
     * @return GroupsListPayload
     */
    protected function createPayload()
    {
        $payload = new GroupsListPayload();
        $payload->setExcludeArchived($this->input->getOption('exclude-archived'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsListPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $groups = $payloadResponse->getGroups();
            $this->output->writeln(sprintf('Received <comment>%d</comment> groups...', count($groups)));
            if (!empty($groups)) {
                $rows = [];
                foreach ($payloadResponse->getGroups() as $group) {
                    $row            = $this->serializeObjectToArray($group);
                    $row['purpose'] = !$group->getPurpose() ?: $group->getPurpose()->getValue();
                    $row['topic']   = !$group->getTopic() ?: $group->getTopic()->getValue();

                    $rows[] = $row;
                }
                $this->renderTable($rows, null);
                $this->writeOk('Finished listing groups');
            } else {
                $this->writeComment('No groups to list');
            }
        } else {
            $this->writeError(sprintf('Failed to list groups. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
