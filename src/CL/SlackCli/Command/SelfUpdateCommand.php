<?php

namespace CL\SlackCli\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends AbstractCommand
{
    const MANIFEST_FILE = 'http://cleentfaar.github.io/slack-cli/manifest.json';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();
        
        $this->setName('self:update');
        $this->setDescription('Updates slack.phar to the latest version');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $currentVersion = $this->getApplication()->getVersion();
        $manager        = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        
        if ($manager->update($currentVersion, true)) {
            $this->writeOk($output, sprintf(
                '<info>Updated Slack CLI from <fg=yellow>%s</fg=yellow> to <fg=yellow>%s</fg=yellow></info>', 
                $currentVersion, 
                $this->getApplication()->getVersion()
            ));
        } else {
            $output->writeln(sprintf(
                '<comment>You are already using the latest version (%s)</comment>', 
                $currentVersion
            ));
        }
    }
}
