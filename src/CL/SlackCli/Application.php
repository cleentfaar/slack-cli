<?php

namespace CL\SlackCli;

use CL\SlackCli\Config\ConfigManager;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends BaseApplication
{
    /**
     * @var string|null
     */
    private $defaultToken;

    /**
     * @var ConfigManager[]
     */
    private $configs = [];

    /**
     * @param string $defaultToken
     */
    public function setDefaultToken($defaultToken)
    {
        $this->defaultToken = $defaultToken;
    }

    /**
     * @return string|null
     */
    public function getDefaultToken()
    {
        return $this->defaultToken;
    }

    /**
     * @param string          $path
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return ConfigManager
     */
    public function getConfig($path, InputInterface $input, OutputInterface $output)
    {
        if (!array_key_exists($path, $this->configs)) {
            $this->configs[$path] = new ConfigManager($path);
        }

        return $this->configs[$path];
    }

    /**
     * Gets the default commands that should always be available.
     *
     * @return array An array of default Command instances
     */
    protected function getDefaultCommands()
    {
        // Keep the core default commands to have the HelpCommand
        // which is used when using the --help option
        $defaultCommands = parent::getDefaultCommands();

        $ownCommands = [
            new Command\ApiTestCommand(),
            new Command\AuthTestCommand(),
            new Command\ChannelsArchiveCommand(),
            new Command\ChannelsCreateCommand(),
            new Command\ChannelsHistoryCommand(),
            new Command\ChannelsInfoCommand(),
            new Command\ChannelsInviteCommand(),
            new Command\ChannelsJoinCommand(),
            new Command\ChannelsKickCommand(),
            new Command\ChannelsLeaveCommand(),
            new Command\ChannelsListCommand(),
            new Command\ChannelsMarkCommand(),
            new Command\ChannelsRenameCommand(),
            new Command\ChannelsSetPurposeCommand(),
            new Command\ChannelsSetTopicCommand(),
            new Command\ChannelsUnarchiveCommand(),
            new Command\ChatDeleteCommand(),
            new Command\ChatPostMessageCommand(),
            new Command\ChatUpdateCommand(),
            new Command\ConfigGetCommand(),
            new Command\ConfigListCommand(),
            new Command\ConfigSetCommand(),
            new Command\EmojiListCommand(),
            new Command\FilesInfoCommand(),
            new Command\FilesListCommand(),
            new Command\FilesUploadCommand(),
            new Command\GroupsArchiveCommand(),
            new Command\GroupsCloseCommand(),
            new Command\GroupsCreateChildCommand(),
            new Command\GroupsCreateCommand(),
            new Command\GroupsHistoryCommand(),
            new Command\GroupsInviteCommand(),
            new Command\GroupsKickCommand(),
            new Command\GroupsLeaveCommand(),
            new Command\GroupsListCommand(),
            new Command\GroupsMarkCommand(),
            new Command\GroupsOpenCommand(),
            new Command\GroupsRenameCommand(),
            new Command\GroupsSetPurposeCommand(),
            new Command\GroupsSetTopicCommand(),
            new Command\GroupsUnarchiveCommand(),
            new Command\ImCloseCommand(),
            new Command\ImHistoryCommand(),
            new Command\ImListCommand(),
            new Command\ImMarkCommand(),
            new Command\ImOpenCommand(),
            new Command\OauthAccessCommand(),
            new Command\PresenceSetCommand(),
            new Command\SearchAllCommand(),
            new Command\SearchFilesCommand(),
            new Command\SearchMessagesCommand(),
            new Command\StarsListCommand(),
            new Command\UsersInfoCommand(),
            new Command\UsersListCommand(),
            new Command\UsersSetActiveCommand(),
        ];

        return array_merge($defaultCommands, $ownCommands);
    }
}
