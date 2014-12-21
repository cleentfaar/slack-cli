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
class ConfigSetCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config.set');
        $this->setDescription('Stores the given key and value in the global configuration');
        $this->addArgument('key', InputArgument::REQUIRED, 'The key to use');
        $this->addArgument('value', InputArgument::REQUIRED, 'The value to set for this key');
        $this->setHelp(<<<EOT
The <info>config.set</info> command lets you store a given key and value in the global configuration.

To list all stored keys and values, use the <info>config.list</info> command.
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $key   = $input->getArgument('key');
        $value = $input->getArgument('value');

        $this->setConfig($key, $value);

        $this->writeOk($output, sprintf(
            'Key <info>%s</info> with value <comment>%s</comment> was saved successfully!',
            $key,
            var_export($value, true)
        ));
    }

    private function setConfig($key, $value)
    {
        // handle config values
        $uniqueConfigValues = [
            'default_token' => ['is_string', function ($val) {
                return $val;
            }],
        ];

        foreach ($uniqueConfigValues as $name => $callbacks) {
            if ($key === $name) {
                list($validator, $normalizer) = $callbacks;

                if (true !== $validation = $validator($value)) {
                    throw new \RuntimeException(sprintf(
                        '"%s" is an invalid value' . ($validation ? ' (' . $validation . ')' : ''),
                        $value
                    ));
                }

                $this->configSource->addConfigSetting($key, $normalizer($value));

                return 0;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Setting "%s" does not exist or is not supported by this command',
            $key
        ));
    }
}
