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

use CL\Slack\Payload\SearchAllPayload;
use CL\Slack\Payload\SearchAllPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchAllCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('search:all');
        $this->setDescription('Searches messages and files within your Slack team');
        $this->addArgument('query', InputArgument::REQUIRED, 'Search query. May contains booleans, etc.');
        $this->addOption('sort', null, InputOption::VALUE_REQUIRED, 'Return matches sorted by either score or timestamp');
        $this->addOption('sort-dir', null, InputOption::VALUE_REQUIRED, 'Change sort direction to ascending (asc) or descending (desc)');
        $this->addOption('highlight', null, InputOption::VALUE_REQUIRED, 'Pass a value of 1 to enable query highlight markers');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return');
        $this->setHelp(<<<EOT
The <info>search:all</info> command allows you to search both messages and files with a single query.

The response returns matches broken down by their type of content, similar to the facebook/gmail auto-completed search widgets.

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/search.all</comment>
EOT
        );
    }

    /**
     * @return SearchAllPayload
     */
    protected function createPayload()
    {
        $payload = new SearchAllPayload();
        $payload->setQuery($this->input->getArgument('query'));
        $payload->setSort($this->input->getOption('sort'));
        $payload->setSortDir($this->input->getOption('sort-dir'));
        $payload->setPage($this->input->getOption('page'));
        $payload->setCount($this->input->getOption('count'));
        $payload->setHighlight($this->input->getOption('highlight'));

        return $payload;
    }

    /**
     * {@inheritdoc}
     *
     * @param SearchAllPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $total = 0;
            if ($messageSearchResult = $payloadResponse->getMessageResult()) {
                $total += $messageSearchResult->getTotal();
            }
            if ($fileSearchResult = $payloadResponse->getFileResult()) {
                $total += $fileSearchResult->getTotal();
            }

            $this->writeComment(sprintf('Got %d results...', $total));

            if ($total > 0) {
                $this->writeComment('Listing messages...');
                if ($messageSearchResult->getTotal() > 1) {
                    $this->renderTable($messageSearchResult->getMatches());
                } else {
                    $this->writeComment('No messages matched the query');
                }

                $this->writeComment('Listing files...');
                if ($fileSearchResult->getTotal() > 1) {
                    $this->renderTable($fileSearchResult->getMatches());
                } else {
                    $this->writeComment('No files matched the query');
                }
            }
        } else {
            $this->writeError(sprintf('Failed to search. %s', lcfirst($payloadResponse->getErrorExplanation())));
        }
    }
}
