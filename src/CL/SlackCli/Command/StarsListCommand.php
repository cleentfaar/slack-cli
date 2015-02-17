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

use CL\Slack\Payload\StarsListPayload;
use CL\Slack\Payload\StarsListPayloadResponse;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class StarsListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('stars:list');
        $this->setDescription('Returns a list of all the items starred by a user');
        $this->addOption('user-id', null, InputOption::VALUE_REQUIRED, 'Show stars by this user. Defaults to the token\'s user.');
        $this->setHelp(<<<EOT
The <info>stars:list</info> returns a list of all the items starred by a user.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/stars.list</comment>
EOT
        );
    }

    /**
     * @return StarsListPayload
     */
    protected function createPayload()
    {
        $payload = new StarsListPayload();
        $payload->setUserId($this->input->getOption('user-id'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param StarsListPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $stars = $payloadResponse->getItems();
            $this->output->writeln(sprintf('Received <comment>%d</comment> starred items...', count($stars)));
            if (!empty($stars)) {
                $this->renderTable($stars, null);
                $this->writeOk('Finished listing starred items');
            } else {
                $this->writeComment('No starred items to list');
            }
        } else {
            $this->writeError(sprintf(
                'Failed to list starred items: %s',
                lcfirst($payloadResponse->getErrorExplanation())
            ));
        }
    }
}
