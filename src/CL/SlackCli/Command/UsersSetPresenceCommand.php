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

use CL\Slack\Payload\UsersSetPresencePayload;
use CL\Slack\Payload\UsersSetPresencePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class UsersSetPresenceCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('users:set-presence');
        $this->setDescription('Override the token user\'s presence value');
        $this->addArgument('presence', InputArgument::REQUIRED, 'Either "active" or "away"');
        $this->setHelp(<<<EOT
The <info>users:set-presence</info> command lets you manually override the token user's presence value.
Consult the presence documentation for more details.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/presence.set</comment>
EOT
        );
    }

    /**
     * @return UsersSetPresencePayload
     */
    protected function createPayload()
    {
        $payload = new UsersSetPresencePayload();
        $payload->setPresence($this->input->getArgument('presence'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersSetPresencePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk('Successfully changed presence!');
        } else {
            $this->writeError(sprintf('Failed to change presence. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
