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

use CL\Slack\Payload\GroupsSetTopicPayload;
use CL\Slack\Payload\GroupsSetTopicPayloadResponse;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsSetTopicCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:set-topic');
        $this->setDescription('Change the topic of a group. The calling user must be a member of the group.');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of the group to change the topic of');
        $this->addArgument('topic', InputArgument::REQUIRED, 'The new topic');
        $this->setHelp(<<<EOT
The <info>groups:set-topic</info> command changes the topic of a group.
The calling user must be a member of the group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.setTopic</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return GroupsSetTopicPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new GroupsSetTopicPayload();
        $payload->setGroupId($input->getArgument('group-id'));
        $payload->setTopic($input->getArgument('topic'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsSetTopicPayloadResponse $payloadResponse
     * @param InputInterface                $input
     * @param OutputInterface               $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, sprintf('Successfully changed topic of group to: "%s"', $payloadResponse->getTopic()));
        } else {
            $this->writeError($output, sprintf('Failed to change topic of group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
