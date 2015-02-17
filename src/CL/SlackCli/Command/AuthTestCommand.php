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

use CL\Slack\Payload\AuthTestPayload;
use CL\Slack\Payload\AuthTestPayloadResponse;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class AuthTestCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('auth:test');
        $this->setDescription('Test authentication with the Slack API and, optionally, tells you who you are (use -v).');
        $this->setHelp(<<<EOT
The <info>auth:test</info> command lets you test authenticating with the Slack API.

Use the verbose option `-v` to also return information about the token's user.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/auth.test</comment>
EOT
        );
    }

    /**
     * @return AuthTestPayload
     */
    protected function createPayload()
    {
        return new AuthTestPayload();
    }

    /**
     * {@inheritdoc}
     *
     * @param AuthTestPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully authenticated by the Slack API!');
            if ($this->output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $data = [
                    'User ID'  => $payloadResponse->getUserId(),
                    'Username' => $payloadResponse->getUsername(),
                    'Team ID'  => $payloadResponse->getTeamId(),
                    'Team'     => $payloadResponse->getTeam(),
                ];
                $this->renderKeyValueTable($data);
            }
        } else {
            $this->writeError(sprintf('Failed to be authenticated by the Slack API: %s', lcfirst($payloadResponse->getErrorExplanation())));

            return 1;
        }
    }
}
