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
class ConfigGetCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config:get');
        $this->setDescription('Retrieves the value that is set for the given setting from the configuration');
        $this->addArgument('setting', InputArgument::REQUIRED, 'The key to use');
        $this->setHelp(<<<EOT
The <info>config:get</info> command retrieves the value that is set for the given setting from the configuration.

To list all stored keys and values, use the <info>config.list</info> command.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $this->input->getArgument('setting');

        if (!$this->getConfig()->has($key)) {
            $this->writeError(sprintf('There is no setting with that name in the configuration: `%s`', $key));

            return 1;
        }

        $value = $this->getConfig()->get($key);

        $this->output->writeln(sprintf(
            'Value of <info>`%s`</info> is <comment>%s</comment>',
            $key,
            is_null($value) ? 'NULL' : '`' . var_export($value, true) . '`'
        ));
    }
}
