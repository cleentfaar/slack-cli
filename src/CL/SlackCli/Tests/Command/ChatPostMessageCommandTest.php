<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChatPostMessageCommand;

class ChatPostMessageCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel' => '#foobar',
            'text'    => 'Hello world!',
        ];

        $this->assertExecutionSucceedsWith($args, [
            'Successfully sent message to Slack',
            'Channel ID: C1234567',
            'Timestamp: 12345678.12345678',
        ]);

        $this->assertExecutionFailsWith($args, 'Failed to send message to Slack');
    }

    protected function createCommand()
    {
        return new ChatPostMessageCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'chat:post-message';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedAliases()
    {
        return [
            'chat.postMessage',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel',
            'text',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedOptions()
    {
        return [
            'icon-emoji',
            'icon-url',
            'link-names',
            'parse',
            'unfurl-links',
            'unfurl-media',
            'username',
        ];
    }
}
