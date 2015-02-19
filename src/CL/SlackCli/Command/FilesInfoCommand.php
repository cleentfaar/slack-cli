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

use CL\Slack\Payload\FilesInfoPayload;
use CL\Slack\Payload\FilesInfoPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class FilesInfoCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('files:info');
        $this->setDescription('Returns information about a file in your Slack team');
        $this->addArgument('file-id', InputArgument::REQUIRED, 'The ID of the file to get information on');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page.');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return.');
        $this->setHelp(<<<EOT
The <info>files:info</info> command returns information about a file in your team.

Each comment object in the comments array contains details about a single comment. Comments are returned oldest first.

The paging information contains the count of comments returned, the total number of comments, the page of results
returned in this response and the total number of pages available.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/files.info</comment>
EOT
        );
    }

    /**
     * @return FilesInfoPayload
     */
    protected function createPayload()
    {
        $payload = new FilesInfoPayload();
        $payload->setFileId($this->input->getArgument('file-id'));
        $payload->setCount($this->input->getOption('count'));
        $payload->setPage($this->input->getOption('page'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesInfoPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $file = $payloadResponse->getFile();
            $this->renderKeyValueTable($file);
        } else {
            $this->writeError(sprintf('Failed to fetch information about the file: %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
