<?php

namespace CL\SlackCli\Console\Command;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends Command
{
    const MANIFEST_FILE = 'http://cleentfaar.github.io/slack-cli/manifest.json';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('self.update');
        $this->setDescription('Updates slack.phar to the latest version');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        $manager->update($this->getApplication()->getVersion(), true);
    }
}
