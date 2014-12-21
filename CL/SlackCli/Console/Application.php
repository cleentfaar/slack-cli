<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Console;

use CL\SlackCli\Command;
use CL\SlackCli\Config\Config;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class Application extends BaseApplication
{
    /**
     * @var Config[]
     */
    private $configs = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('Slack CLI', $this->getReplacedVersion());

        $this->add(new Command\ApiTestCommand());
        $this->add(new Command\AuthTestCommand());
        $this->add(new Command\ChannelsArchiveCommand());
        $this->add(new Command\ChannelsCreateCommand());
        $this->add(new Command\ChannelsHistoryCommand());
        $this->add(new Command\ChannelsInfoCommand());
        $this->add(new Command\ChannelsInviteCommand());
        $this->add(new Command\ChannelsJoinCommand());
        $this->add(new Command\ChannelsKickCommand());
        $this->add(new Command\ChannelsLeaveCommand());
        $this->add(new Command\ChannelsListCommand());
        $this->add(new Command\ChannelsMarkCommand());
        $this->add(new Command\ChannelsRenameCommand());
        $this->add(new Command\ChannelsSetPurposeCommand());
        $this->add(new Command\ChannelsSetTopicCommand());
        $this->add(new Command\ChannelsUnarchiveCommand());
        $this->add(new Command\ChatDeleteCommand());
        $this->add(new Command\ChatPostMessageCommand());
        $this->add(new Command\ChatUpdateCommand());
        $this->add(new Command\ConfigEditCommand());
        $this->add(new Command\ConfigGetCommand());
        $this->add(new Command\ConfigListCommand());
        $this->add(new Command\ConfigSetCommand());
        $this->add(new Command\ConfigUnsetCommand());
        $this->add(new Command\EmojiListCommand());
        $this->add(new Command\FilesInfoCommand());
        $this->add(new Command\FilesListCommand());
        $this->add(new Command\FilesUploadCommand());
        $this->add(new Command\GroupsArchiveCommand());
        $this->add(new Command\GroupsCloseCommand());
        $this->add(new Command\GroupsCreateChildCommand());
        $this->add(new Command\GroupsCreateCommand());
        $this->add(new Command\GroupsHistoryCommand());
        $this->add(new Command\GroupsInviteCommand());
        $this->add(new Command\GroupsKickCommand());
        $this->add(new Command\GroupsLeaveCommand());
        $this->add(new Command\GroupsListCommand());
        $this->add(new Command\GroupsMarkCommand());
        $this->add(new Command\GroupsOpenCommand());
        $this->add(new Command\GroupsRenameCommand());
        $this->add(new Command\GroupsSetPurposeCommand());
        $this->add(new Command\GroupsSetTopicCommand());
        $this->add(new Command\GroupsUnarchiveCommand());
        $this->add(new Command\ImCloseCommand());
        $this->add(new Command\ImHistoryCommand());
        $this->add(new Command\ImListCommand());
        $this->add(new Command\ImMarkCommand());
        $this->add(new Command\ImOpenCommand());
        $this->add(new Command\OauthAccessCommand());
        $this->add(new Command\PresenceSetCommand());
        $this->add(new Command\SearchAllCommand());
        $this->add(new Command\SearchFilesCommand());
        $this->add(new Command\SearchMessagesCommand());
        $this->add(new Command\SelfUpdateCommand());
        $this->add(new Command\StarsListCommand());
        $this->add(new Command\UsersInfoCommand());
        $this->add(new Command\UsersListCommand());
        $this->add(new Command\UsersSetActiveCommand());
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        return sprintf('%s by <comment>Cas Leentfaar</comment>', parent::getLongVersion());
    }

    /**
     * @return null|string
     */
    private function getReplacedVersion()
    {
        $version = '@git-version@';
        if ($version === '@' . 'git-version@') {
            return 'UNKNOWN';
        }

        return $version;
    }

    /**
     * @param string          $path
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return Config
     */
    public function getConfig($path, InputInterface $input, OutputInterface $output)
    {
        if (!array_key_exists($path, $this->configs)) {
            $this->configs[$path] = new Config($path);
        }

        return $this->configs[$path];
    }
}
