<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChatUpdateCommand;

class ChatUpdateCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'timestamp'  => '12345678.12345678',
            'text'       => 'New text of the message',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully updated message');
        $this->assertExecutionFailsWith($args, 'Failed to update message');
    }

    protected function createCommand()
    {
        return new ChatUpdateCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'chat:update';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
            'timestamp',
            'text',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedOptions()
    {
        return [];
    }
}
