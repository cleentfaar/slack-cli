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

use CL\Slack\Payload\GroupsOpenPayload;
use CL\Slack\Payload\GroupsOpenPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsOpenCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:open');
        $this->setDescription('Opens a given Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of a private group to open');
        $this->setHelp(<<<EOT
The <info>groups.open</info> command let's you open a given Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.open</comment>
EOT
        );
    }

    /**
     * @return GroupsOpenPayload
     */
    protected function createPayload()
    {
        $payload = new GroupsOpenPayload();
        $payload->setGroupId($this->input->getArgument('group-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsOpenPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyOpen()) {
                $this->output->writeln('<comment>Couldn\'t open group: the group has already been opened</comment>');
            } else {
                $this->writeOk('Successfully opened group!');
            }
        } else {
            $this->writeError(sprintf('Failed to open group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
