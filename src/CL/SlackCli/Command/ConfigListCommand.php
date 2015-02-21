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

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ConfigListCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config:list');
        $this->setDescription('Lists all the keys and values from the global configuration');
        $this->setHelp(<<<EOT
The <info>config:list</info> command lists all the keys and values from the global configuration.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $rows = $this->extractConfiguration($this->config->all(), $this->config->raw(), $output);
        
        $this->createKeyValueTable($rows)->render();
    }

    /**
     * Extracts the contents of the configuration file recursively
     *
     * @param array           $contents
     * @param array           $rawContents
     * @param OutputInterface $output
     * @param string|null     $k
     * @param array           $currentRows
     *
     * @return array
     */
    private function extractConfiguration(
        array $contents,
        array $rawContents,
        OutputInterface $output,
        $k = null,
        &$currentRows = []
    ) {
        $origK = $k;
        foreach ($contents as $key => $value) {
            if ($k === null && !in_array($key, array('config'))) {
                continue;
            }

            $rawVal = isset($rawContents[$key]) ? $rawContents[$key] : null;

            if (is_array($value) && (!is_numeric(key($value)))) {
                $k .= preg_replace('{^config\.}', '', $key . '.');
                $this->extractConfiguration($value, $rawVal, $output, $k, $currentRows);

                if (substr_count($k, '.') > 1) {
                    $k = str_split($k, strrpos($k, '.', -2));
                    $k = $k[0] . '.';
                } else {
                    $k = $origK;
                }

                continue;
            }

            if (is_array($value)) {
                $value = array_map(function ($val) {
                    return is_array($val) ? json_encode($val) : $val;
                }, $value);

                $value = '[' . implode(', ', $value) . ']';
            }

            if (is_bool($value)) {
                $value = var_export($value, true);
            }

            if (is_string($rawVal) && $rawVal != $value) {
                $currentRows[$k . $key] = $rawVal . ' (' . $value . ')';
            } else {
                $currentRows[$k . $key] = $value;
            }
        }

        return $currentRows;
    }
}
