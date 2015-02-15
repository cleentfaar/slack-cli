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

use CL\Slack\Payload\GroupsInvitePayload;
use CL\Slack\Payload\GroupsInvitePayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsInviteCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:invite');
        $this->setDescription('Invites a user to a group. The token\'s user must be a member of the group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to invite the user into');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'The ID of the user to invite');
        $this->setHelp(<<<EOT
The <info>groups:invite</info> command is used to invite a user to a private group.
The calling user must be a member of the group.

To invite a new member to a group without giving them access to the archives of the group
run the <info>groups.createChild</info> command before inviting.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.invite</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return GroupsInvitePayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new GroupsInvitePayload();
        $payload->setGroupId($input->getArgument('group-id'));
        $payload->setUserId($input->getArgument('user-id'));
        
        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsInvitePayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->getAlreadyInGroup()) {
                $output->writeln('<comment>The given user is already in this group</comment>');
            } else {
                $this->writeOk($output, 'Successfully invited user to the group!');
                if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                    $output->writeln('Group used:');
                    $data = $this->serializeObjectToArray($payloadResponse->getGroup());
                    $this->renderKeyValueTable($output, $data);
                }
            }
        } else {
            $this->writeError($output, sprintf('Failed to invite user: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
