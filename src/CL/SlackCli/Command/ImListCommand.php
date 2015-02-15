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
use CL\Slack\Payload\PayloadInterface;
use CL\Slack\Payload\PayloadResponseInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param InputInterface $input
     *
     * @return ImListPayload
     */
    protected function createPayload(InputInterface $input)
    {
        $payload = new ImListPayload();
        $payload->setExcludeArchived($input->getOption('exclude-archived'));
        
        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param ImListPayloadResponse $payloadResponse
     * @param InputInterface              $input
     * @param OutputInterface             $output
     */
    protected function handleResponse(PayloadResponseInterface $payloadResponse, InputInterface $input, OutputInterface $output)
    {
        if ($payloadResponse->isOk()) {
            $channels = $payloadResponse->getImChannels();
            if (!empty($channels)) {
                $this->renderTable($output, $channels, null);
                $this->writeOk($output, 'Finished listing channels');
            } else {
                $this->writeComment($output, 'No IM channels to list');
            }
        } else {
            $this->writeError($output, sprintf('Failed to list channels. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
