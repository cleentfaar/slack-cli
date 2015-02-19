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

        $this->setName('config:set');
        $this->setDescription('Stores the given setting and value in the global configuration');
        $this->addArgument('setting', InputArgument::REQUIRED, 'The setting to use');
        $this->addArgument('value', InputArgument::REQUIRED, 'The value to set for this setting');
        $this->setHelp(<<<EOT
The <info>config:set</info> command lets you store a given setting and value in the global configuration.

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
        $value   = $this->input->getArgument('value');

        return $this->setConfig($setting, $value);
    }

    private function setConfig($setting, $value)
    {
        // handle config values
        $uniqueConfigValues = [
            'default_token' => ['is_string', function ($val) {
                return $val;
            }],
        ];

        foreach ($uniqueConfigValues as $name => $callbacks) {
            if ($setting === $name) {
                list($validator, $normalizer) = $callbacks;

                if (true !== $validation = $validator($value)) {
                    throw new \RuntimeException(sprintf(
                        '"%s" is an invalid value' . ($validation ? ' (' . $validation . ')' : ''),
                        $value
                    ));
                }

                $this->configSource->addConfigSetting($setting, $normalizer($value));

                $this->writeOk(sprintf(
                    'Successfully changed value of <info>`%s`</info> to <comment>`%s`</comment>!',
                    $setting,
                    $value
                ));

                return 0;
            }
        }

        $this->writeError(sprintf('There is no setting with that name in the configuration: `%s`', $setting));

        return 1;
    }
}
