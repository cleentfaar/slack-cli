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

use CL\Slack\Payload\UsersInfoPayload;
use CL\Slack\Payload\UsersInfoPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class UsersInfoCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('users:info');
        $this->setDescription('Returns information about a team member');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'User to get info on');
        $this->setHelp(<<<EOT
The <info>users.info</info> command returns information about a team member.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/users.info</comment>
EOT
        );
    }

    /**
     * @return UsersInfoPayload
     */
    protected function createPayload()
    {
        $payload = new UsersInfoPayload();
        $payload->setUserId($this->input->getArgument('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersInfoPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->renderKeyValueTable($payloadResponse->getUser());
        } else {
            $this->writeError(sprintf('Failed to fetch information about the user: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
