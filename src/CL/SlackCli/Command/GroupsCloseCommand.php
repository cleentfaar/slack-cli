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

use CL\Slack\Payload\GroupsClosePayload;
use CL\Slack\Payload\GroupsClosePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsCloseCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:close');
        $this->setDescription('Closes a given Slack group');
        $this->addArgument('group-id', InputArgument::REQUIRED, 'The ID of a private group to close');
        $this->setHelp(<<<EOT
The <info>groups:close</info> command let's you close a given Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.close</comment>
EOT
        );
    }

    /**
     * @return GroupsClosePayload
     */
    protected function createPayload()
    {
        $payload = new GroupsClosePayload();
        $payload->setGroupId($this->input->getArgument('group-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsClosePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyClosed()) {
                $this->output->writeln('<comment>Couldn\'t close group: the group has already been closed</comment>');
            } else {
                $this->writeOk('Successfully closed group!');
            }
        } else {
            $this->writeError(sprintf('Failed to close group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
