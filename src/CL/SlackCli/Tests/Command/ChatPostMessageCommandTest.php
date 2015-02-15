<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChatPostMessageCommand;
use Symfony\Component\Console\Output\OutputInterface;

class ChatPostMessageCommandTest extends AbstractApiCommandTest
{
    protected function createCommand()
    {
        return new ChatPostMessageCommand();
    }

    protected function getExpectedName()
    {
        return 'chat:post-message';
    }

    protected function getExpectedAliases()
    {
        return [
            'chat.postMessage',
        ];
    }

    public function testExecute()
    {
        $this->assertExecutionSucceedsWith(
            [
                'channel' => '#foobar',
                'text'    => 'Hello world!',
            ],
            [
                'Successfully sent message to Slack',
                'Channel ID: C1234567',
                'Timestamp: 12345678.12345678',
            ]
        );

        $this->assertExecutionFailsWith([
            'channel' => '#foobar',
            'text'    => 'Hello world!',
        ], 'Failed to send message to Slack');
    }
}
