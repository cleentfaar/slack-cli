<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Console\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableHelper;
use Symfony\Component\Console\Input\InputArgument;
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

        $this->setName('config.list');
        $this->setDescription('Lists all the keys and values from the global configuration');
        $this->setHelp(<<<EOT
The <info>config.list</info> command lists all the keys and values from the global configuration.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $this->getApplication()->getConfig($input->getOption('configuration-path'), $input, $output);
        $table  = $this->createKeyValueTable($output, $config->all());
        $table->setStyle('borderless');
        $table->render();
    }
}
