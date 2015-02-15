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
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param InputInterface $input
     *
     * @return UsersInfoPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new UsersInfoPayload();
        $payload->setUserId($input->getArgument('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param UsersInfoPayloadResponse $payloadResponse
     * @param InputInterface           $input
     * @param OutputInterface          $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $this->renderKeyValueTable($output, $payloadResponse->getUser());
        } else {
            $this->writeError($output, sprintf('Failed to fetch information about the user: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
