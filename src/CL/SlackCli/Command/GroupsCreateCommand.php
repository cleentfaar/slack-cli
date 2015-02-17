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

use CL\Slack\Payload\GroupsCreatePayload;
use CL\Slack\Payload\GroupsCreatePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsCreateCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('groups:create');
        $this->setDescription('Creates a new Slack group with the given name');
        $this->addArgument('name', InputArgument::REQUIRED, 'The name of the private group to create');
        $this->setHelp(<<<EOT
The <info>groups:create</info> command let's you create a new Slack group.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/groups.create</comment>
EOT
        );
    }

    /**
     * @return GroupsCreatePayload
     */
    protected function createPayload()
    {
        $payload = new GroupsCreatePayload();
        $payload->setName($this->input->getArgument('name'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param GroupsCreatePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully created group!');
            $this->renderKeyValueTable($payloadResponse->getGroup());
        } else {
            $this->writeError(sprintf('Failed to create group: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
