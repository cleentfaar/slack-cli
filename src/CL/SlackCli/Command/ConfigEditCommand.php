<?php

namespace CL\SlackCli\Command;

use CL\SlackCli\Config\Config;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ConfigEditCommand extends AbstractCommand
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->setName('config.edit');
        $this->setDescription('Edit config options');

        $this->setHelp(<<<EOT
This command allows you to edit the Slack CLI settings using a pre-configured editor.

To choose your editor you can set the "EDITOR" env variable.

To get a list of configuration values in the file:

    <comment>slack.phar config.list</comment>
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Open file in editor
        $editor = escapeshellcmd(getenv('EDITOR'));
        if (!$editor) {
            if (defined('PHP_WINDOWS_VERSION_BUILD')) {
                $editor = 'notepad';
            } else {
                foreach (['vim', 'vi', 'nano', 'pico', 'ed'] as $candidate) {
                    if (exec('which ' . $candidate)) {
                        $editor = $candidate;
                        break;
                    }
                }
            }
        }

        $file = $this->configFile->getPath();
        system($editor . ' ' . $file . (defined('PHP_WINDOWS_VERSION_BUILD') ? '' : ' > `tty`'));
    }
}
