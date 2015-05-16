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

        $this->setName('config:unset');
        $this->setDescription('Removes the given setting from the configuration');
        $this->addArgument('setting', InputArgument::REQUIRED, 'The setting to remove');
        $this->setHelp(<<<EOT
The <info>config:unset</info> command lets you remove a given setting (and it's value) in the global configuration.

To list all stored settings and values, use the <info>config.list</info> command.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $setting = $this->input->getArgument('setting');

        if (!$this->getConfig()->has($setting)) {
            $this->writeComment(sprintf('No changes made; there is no setting defined with the name `%s`', $setting));
        } else {
            $this->getConfigSource()->removeConfigSetting($setting);

            $this->writeOk(sprintf('Setting with name <info>`%s`</info> has been removed from the configuration!', $setting));
        }
    }
}
