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

use CL\Slack\Payload\GroupsArchivePayload;
use CL\Slack\Payload\GroupsArchivePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsArchiveCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:archive');
        $this->setDescription('Archives a given Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of a private group to archive');
        $this->setHelp(<<<EOT
The <info>groups:archive</info> command let's you archive a given Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.archive</comment>
EOT
        );
    }

    /**
     * @return GroupsArchivePayload
     */
    protected function createPayload()
    {
        $payload = new GroupsArchivePayload();
        $payload->setGroupId($this->input->getArgument('group-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsArchivePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully archived group!');
        } else {
            $this->writeError(sprintf('Failed to archive group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
