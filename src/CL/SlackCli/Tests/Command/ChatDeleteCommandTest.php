<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChatDeleteCommand;

class ChatDeleteCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'timestamp'  => '12345678.12345678',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully deleted message');
        $this->assertExecutionFailsWith($args, 'Failed to delete message');
    }

    protected function createCommand()
    {
        return new ChatDeleteCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'chat:delete';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
            'timestamp',
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
