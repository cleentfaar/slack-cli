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

use CL\Slack\Payload\SearchFilesPayload;
use CL\Slack\Payload\SearchFilesPayloadResponse;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class SearchFilesCommand extends AbstractApiCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('search:files');
        $this->setDescription('Searches files within your Slack team');
        $this->addArgument('query', InputArgument::REQUIRED, 'Search query. May contains booleans, etc.');
        $this->addOption('sort', null, InputOption::VALUE_REQUIRED, 'Return matches sorted by either score or timestamp');
        $this->addOption('sort-dir', null, InputOption::VALUE_REQUIRED, 'Change sort direction to ascending (asc) or descending (desc)');
        $this->addOption('highlight', null, InputOption::VALUE_REQUIRED, 'Pass a value of 1 to enable query highlight markers');
        $this->addOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of items to return per page');
        $this->addOption('page', 'p', InputOption::VALUE_REQUIRED, 'Page number of results to return');
        $this->setHelp(<<<EOT
The <info>search:files</info> command allows you to search for files matching a given query

If the `--highlight` option is specified, the matching query terms will be marked up in the results so that clients may
replace them with appropriate highlighting markers (e.g. <span class="highlight"></span>).

The UTF-8 markers used are:
- start: "\xEE\x80\x80"; # U+E000 (private-use)
- end  : "\xEE\x80\x81"; # U+E001 (private-use)

For more information about the related API method, check out the official documentation:
<comment>https://api.slack.com/methods/search.files</comment>
EOT
        );
    }

    /**
     * @return SearchFilesPayload
     */
    protected function createPayload()
    {
        $payload = new SearchFilesPayload();
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
     * @param SearchFilesPayloadResponse $payloadResponse
     */
    protected function handleResponse($payloadResponse)
    {
        if ($payloadResponse->isOk()) {
            $total = 0;
            if ($fileSearchResult = $payloadResponse->getResult()) {
                $total += $fileSearchResult->getTotal();
            }

            $this->writeComment(sprintf('Got %d results...', $total));

            if ($total > 0) {
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
