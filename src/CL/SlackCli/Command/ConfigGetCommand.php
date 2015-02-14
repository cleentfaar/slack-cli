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
        $this->setDescription('Retrieves the value that is set for the given key from the global configuration');
        $this->addArgument('key', InputArgument::REQUIRED, 'The key to use');
        $this->setHelp(<<<EOT
The <info>config:get</info> command retrieves the value that is set for the given key from the global configuration.

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

        if (!$this->config->has($key)) {
            $this->writeError($output, sprintf('There is no key with that name in the configuration: %s', $key));

            return 1;
        }

        $value = $this->config->get($key);

        $output->writeln(sprintf(
            'Value for <info>%s</info>: <comment>%s</comment>',
            $key,
            var_export($value, true),
            gettype($value)
        ));
    }
}
