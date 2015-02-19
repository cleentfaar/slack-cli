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

use CL\Slack\Payload\FilesListPayload;
use CL\Slack\Payload\FilesListPayloadResponse;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class FilesListCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('files:list');
        $this->setDescription('Returns a list of all files in your Slack team');
        $this->addOption('user-id', 'u', InputOption::VALUE_REQUIRED, 'Filter files created by a single user.');
        $this->addOption('from', null, InputOption::VALUE_REQUIRED, 'Filter files created after this timestamp (inclusive).');
        $this->addOption('to', null, InputOption::VALUE_REQUIRED, 'Filter files created before this timestamp (inclusive).');
        $this->addOption('types', null, InputOption::VALUE_REQUIRED, 'Filter files by type. You can pass multiple values in the types argument, like types=posts,snippets. The default value is all, which does not filter the list.');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page.');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return.');
        $this->setHelp(<<<EOT
The <info>files:list</info> command returns a list of files within the team.
It can be filtered and sliced in various ways.

The response contains a list of files, followed by some paging information.

- Files are always returned with the most recent first.
- Paging contains:
  - the count of files returned
  - the total number of files matching the filter(s) (if any were supplied)
  - the page of results returned in this response
  - the total number of pages available
EOT
        );
    }

    /**
     * @return FilesListPayload
     */
    protected function createPayload()
    {
        $payload = new FilesListPayload();
        $payload->setUserId($this->input->getOption('user-id'));
        $payload->setCount($this->input->getOption('count'));
        $payload->setPage($this->input->getOption('page'));
        $payload->setTimestampFrom($this->input->getOption('from'));
        $payload->setTimestampTo($this->input->getOption('to'));
        $payload->setTypes($this->input->getOption('types'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param FilesListPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $files = $payloadResponse->getFiles();
            $this->writeOk(sprintf('Received <comment>%d</comment> files...', count($files)));
            if (!empty($files)) {
                $this->output->writeln('Listing files...');
                $this->renderTable($files, null);
            }
            if ($payloadResponse->getPaging()) {
                $this->output->writeln('Paging...');
                $this->renderKeyValueTable($payloadResponse->getPaging());
            }
        } else {
            $this->writeError(sprintf('Failed to list files. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        return 'files.list';
    }
}
