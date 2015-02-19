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

        $this->setName('config:edit');
        $this->setDescription('Edit config options');

        $this->setHelp(<<<EOT
The <info>config:edit</info> command allows you to edit the Slack CLI settings using a pre-configured editor.

To choose your editor you can set the "SLACK_CONFIG_EDITOR" environment variable.

To get a list of configuration values in the file, use the `config:list` command:

    <comment>slack.phar config:list</comment>
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $editor = escapeshellcmd(getenv('SLACK_CONFIG_EDITOR'));
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

        $file    = $this->configFile->getPath();
        $to      = defined('PHP_WINDOWS_VERSION_BUILD') ? '' : ' > `tty`';
        $command = sprintf('%s %s%s', $editor, $file, $to);

        $this->output->writeln(sprintf('Editing `%s` using `%s`...', $file, $editor));

        if (!$this->isTest()) {
            system($command);
        }
    }
}
