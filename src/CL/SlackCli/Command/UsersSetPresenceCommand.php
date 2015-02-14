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

use CL\Slack\Payload\PayloadResponseInterface;
use CL\Slack\Payload\UsersSetPresencePayload;
use CL\Slack\Payload\UsersSetPresencePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param InputInterface $input
     *
     * @return UsersSetPresencePayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new UsersSetPresencePayload();
        $payload->setPresence($input->getArgument('presence'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersSetPresencePayloadResponse $payloadResponse
     * @param InputInterface                  $input
     * @param OutputInterface                 $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->writeOk($output, 'Successfully changed presence!');
        } else {
            $this->writeError($output, sprintf('Failed to change presence. %s', $payloadResponse->getErrorExplanation()));
        }
    }
}
