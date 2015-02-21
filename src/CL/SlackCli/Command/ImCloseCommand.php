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

use CL\Slack\Payload\ImClosePayload;
use CL\Slack\Payload\ImClosePayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ImCloseCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('im:close');
        $this->setDescription('Closes a given Slack IM channel');
        $this->addArgument('im-id', InputArgument::REQUIRED, 'The ID of an IM channel to close');
        $this->setHelp(<<<EOT
The <info>im:close</info> command let's you close a IM channel

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/im.close</comment>
EOT
        );
    }

    /**
     * @return ImClosePayload
     */
    protected function createPayload()
    {
        $payload = new ImClosePayload();
        $payload->setImId($this->input->getArgument('im-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ImClosePayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            if ($payloadResponse->isAlreadyClosed()) {
                $this->output->writeln('<comment>Couldn\'t close IM channel: the channel has already been closed</comment>');
            } else {
                $this->writeOk('Successfully closed IM channel!');
            }
        } else {
            $this->writeError(sprintf('Failed to close IM channel: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
