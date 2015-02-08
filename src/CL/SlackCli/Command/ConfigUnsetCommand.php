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

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ConfigUnsetCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config.unset');
        $this->setDescription('Removes the given key from the global configuration');
        $this->addArgument('key', InputArgument::REQUIRED, 'The key to remove');
        $this->setHelp(<<<EOT
The <info>config.remove</info> command lets you remove a given key (and it's value) in the global configuration.

To list all stored keys and values, use the <info>config.list</info> command.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('key');

        $this->configSource->removeConfigSetting($key);

        $this->writeOk($output, sprintf('Key <info>%s</info> with value <comment>%s</comment> was saved successfully!', $key, var_export($value, true)));
    }
}
