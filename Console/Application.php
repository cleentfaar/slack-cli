<?php

namespace CL\SlackCli\Console;

use CL\SlackCli\Console\Command\ApiTestCommand;
use CL\SlackCli\Console\Command\AuthTestCommand;
use CL\SlackCli\Console\Command\ChannelsArchiveCommand;
use CL\SlackCli\Console\Command\ChannelsCreateCommand;
use CL\SlackCli\Console\Command\ChannelsHistoryCommand;
use CL\SlackCli\Console\Command\ChannelsInfoCommand;
use CL\SlackCli\Console\Command\ChannelsInviteCommand;
use CL\SlackCli\Console\Command\ChannelsJoinCommand;
use CL\SlackCli\Console\Command\ChannelsKickCommand;
use CL\SlackCli\Console\Command\ChannelsLeaveCommand;
use CL\SlackCli\Console\Command\ChannelsListCommand;
use CL\SlackCli\Console\Command\ChannelsMarkCommand;
use CL\SlackCli\Console\Command\ChannelsRenameCommand;
use CL\SlackCli\Console\Command\ChannelsSetPurposeCommand;
use CL\SlackCli\Console\Command\ChannelsSetTopicCommand;
use CL\SlackCli\Console\Command\ChannelsUnarchiveCommand;
use CL\SlackCli\Console\Command\ChatDeleteCommand;
use CL\SlackCli\Console\Command\ChatPostMessageCommand;
use CL\SlackCli\Console\Command\ChatUpdateCommand;
use CL\SlackCli\Console\Command\EmojiListCommand;
use CL\SlackCli\Console\Command\FilesInfoCommand;
use CL\SlackCli\Console\Command\FilesListCommand;
use CL\SlackCli\Console\Command\FilesUploadCommand;
use CL\SlackCli\Console\Command\GroupsArchiveCommand;
use CL\SlackCli\Console\Command\GroupsCloseCommand;
use CL\SlackCli\Console\Command\GroupsCreateChildCommand;
use CL\SlackCli\Console\Command\GroupsCreateCommand;
use CL\SlackCli\Console\Command\GroupsHistoryCommand;
use CL\SlackCli\Console\Command\GroupsInviteCommand;
use CL\SlackCli\Console\Command\GroupsKickCommand;
use CL\SlackCli\Console\Command\GroupsLeaveCommand;
use CL\SlackCli\Console\Command\GroupsListCommand;
use CL\SlackCli\Console\Command\GroupsMarkCommand;
use CL\SlackCli\Console\Command\GroupsOpenCommand;
use CL\SlackCli\Console\Command\GroupsRenameCommand;
use CL\SlackCli\Console\Command\GroupsSetPurposeCommand;
use CL\SlackCli\Console\Command\GroupsSetTopicCommand;
use CL\SlackCli\Console\Command\GroupsUnarchiveCommand;
use CL\SlackCli\Console\Command\ImCloseCommand;
use CL\SlackCli\Console\Command\ImHistoryCommand;
use CL\SlackCli\Console\Command\ImListCommand;
use CL\SlackCli\Console\Command\ImMarkCommand;
use CL\SlackCli\Console\Command\ImOpenCommand;
use CL\SlackCli\Console\Command\OauthAccessCommand;
use CL\SlackCli\Console\Command\PresenceSetCommand;
use CL\SlackCli\Console\Command\SearchAllCommand;
use CL\SlackCli\Console\Command\SearchFilesCommand;
use CL\SlackCli\Console\Command\SearchMessagesCommand;
use CL\SlackCli\Console\Command\StarsListCommand;
use CL\SlackCli\Console\Command\UsersInfoCommand;
use CL\SlackCli\Console\Command\UsersListCommand;
use CL\SlackCli\Console\Command\UsersSetActiveCommand;
use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Application extends BaseApplication
{
    /**
     * @var string|null
     */
    private $defaultToken;

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
            new ApiTestCommand(),
            new AuthTestCommand(),
            new ChannelsArchiveCommand(),
            new ChannelsCreateCommand(),
            new ChannelsHistoryCommand(),
            new ChannelsInfoCommand(),
            new ChannelsInviteCommand(),
            new ChannelsJoinCommand(),
            new ChannelsKickCommand(),
            new ChannelsLeaveCommand(),
            new ChannelsListCommand(),
            new ChannelsMarkCommand(),
            new ChannelsRenameCommand(),
            new ChannelsSetPurposeCommand(),
            new ChannelsSetTopicCommand(),
            new ChannelsUnarchiveCommand(),
            new ChatDeleteCommand(),
            new ChatPostMessageCommand(),
            new ChatUpdateCommand(),
            new EmojiListCommand(),
            new FilesInfoCommand(),
            new FilesListCommand(),
            new FilesUploadCommand(),
            new GroupsArchiveCommand(),
            new GroupsCloseCommand(),
            new GroupsCreateChildCommand(),
            new GroupsCreateCommand(),
            new GroupsHistoryCommand(),
            new GroupsInviteCommand(),
            new GroupsKickCommand(),
            new GroupsLeaveCommand(),
            new GroupsListCommand(),
            new GroupsMarkCommand(),
            new GroupsOpenCommand(),
            new GroupsRenameCommand(),
            new GroupsSetPurposeCommand(),
            new GroupsSetTopicCommand(),
            new GroupsUnarchiveCommand(),
            new ImCloseCommand(),
            new ImHistoryCommand(),
            new ImListCommand(),
            new ImMarkCommand(),
            new ImOpenCommand(),
            new OauthAccessCommand(),
            new PresenceSetCommand(),
            new SearchAllCommand(),
            new SearchFilesCommand(),
            new SearchMessagesCommand(),
            new StarsListCommand(),
            new UsersInfoCommand(),
            new UsersListCommand(),
            new UsersSetActiveCommand(),
        ];

        return array_merge($defaultCommands, $ownCommands);
    }
}
