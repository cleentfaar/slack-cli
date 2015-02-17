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

use CL\Slack\Payload\ImListPayload;
use CL\Slack\Payload\ImListPayloadResponse;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ImListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('im:list');
        $this->setDescription('Returns a list of all IM channels in your Slack team');
        $this->addOption('exclude-archived', null, InputOption::VALUE_OPTIONAL, 'Don\'t return archived IM channels.');
        $this->setHelp(<<<EOT
The <info>im:list</info> command returns a list of all IM channels in your Slack team.
This includes channels the caller is in, channels they are not currently in, and archived channels.
The number of (non-deactivated) members in each channel is also returned.
EOT
        );
    }

    /**
     * @return ImListPayload
     */
    protected function createPayload()
    {
        $payload = new ImListPayload();
        $payload->setExcludeArchived($this->input->getOption('exclude-archived'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ImListPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $channels = $payloadResponse->getImChannels();
            if (!empty($channels)) {
                $this->renderTable($channels, null);
                $this->writeOk('Finished listing channels');
            } else {
                $this->writeComment('No IM channels to list');
            }
        } else {
            $this->writeError(sprintf('Failed to list channels. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
