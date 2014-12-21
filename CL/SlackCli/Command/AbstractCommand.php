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

use CL\SlackCli\Config\Config;
use CL\SlackCli\Config\ConfigFactory;
use CL\SlackCli\Console\Application;
use Composer\Config\JsonConfigSource;
use Composer\IO\NullIO;
use Composer\Json\JsonFile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
abstract class AbstractCommand extends Command
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var JsonFile
     */
    protected $configFile;

    /**
     * @var JsonConfigSource
     */
    protected $configSource;

    /**
     * @var string
     */
    protected $configPath;

    /**
     * {@inheritdoc}
     *
     * @return Application
     */
    public function getApplication()
    {
        return parent::getApplication();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->addOption(
            'configuration-path',
            null,
            InputOption::VALUE_REQUIRED,
            'Configuration file to use during this command, defaults to the global configuration path',
            $this->configPath
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->config = ConfigFactory::createConfig(new NullIO());

        // Get the local composer.json, global config.json, or if the user
        // passed in a file to use
        $configFile = $input->getOption('configuration-path') ?: ($this->config->get('home') . '/config.json');

        $this->configPath   = $configFile;
        $this->configFile   = new JsonFile($configFile);
        $this->configSource = new JsonConfigSource($this->configFile);

        // initialize the global file if it's not there
        if (!$this->configFile->exists()) {
            touch($this->configFile->getPath());
            $this->configFile->write(['config' => new \ArrayObject]);
            @chmod($this->configFile->getPath(), 0600);
        }

        if (!$this->configFile->exists()) {
            throw new \RuntimeException('No config.json found in the current directory');
        }
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    protected function writeOk(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('<fg=green>✔</fg=green> %s', $message));
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    protected function writeComment(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('<comment>%s</comment>', $message));
    }

    /**
     * @param OutputInterface $output
     * @param string          $message
     */
    protected function writeError(OutputInterface $output, $message)
    {
        $output->writeln(sprintf('<fg=red>✘</fg=red> %s', $message));
    }

    /**
     * @param OutputInterface $output
     *
     * @return Table
     */
    protected function createTable(OutputInterface $output)
    {
        $table = new Table($output);

        return $table;
    }

    /**
     * @param OutputInterface $output
     * @param array           $keysValues
     *
     * @return Table
     */
    protected function createKeyValueTable(OutputInterface $output, array $keysValues)
    {
        $table = $this->createTable($output);
        $table->setHeaders(['Key', 'Value']);
        foreach ($keysValues as $key => $value) {
            $table->addRow([$key, $value]);
        }

        return $table;
    }
}
