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

use CL\Slack\Payload\ImOpenPayload;
use CL\Slack\Payload\ImOpenPayloadResponse;
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ImOpenCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('im:open');
        $this->setDescription('Opens a Slack IM channel with another user');
        $this->addArgument('user-id', InputArgument::REQUIRED, 'ID of the user to open a direct message channel with');
        $this->setHelp(<<<EOT
The <info>im:open</info> command let's you open a Slack IM channel.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/im.open</comment>
EOT
        );
    }

    /**
     * @param InputInterface $input
     *
     * @return ImOpenPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ImOpenPayload();
        $payload->setUserId($input->getArgument('user-id'));
    }

    /**
     * {@inheritdoc}
     *
     * @param ImOpenPayloadResponse $payloadResponse
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyOpen()) {
                $output->writeln('<comment>Couldn\'t open IM channel: the IM has already been opened</comment>');
            } else {
                $this->writeOk($output, 'Successfully opened IM channel!');
            }
        } else {
            $this->writeError($output, sprintf('Failed to open IM channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
