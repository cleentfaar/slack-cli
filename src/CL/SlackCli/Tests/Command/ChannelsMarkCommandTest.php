<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsMarkCommand;

class ChannelsMarkCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'timestamp'  => '12345678.12345678',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully moved the read cursor');
        $this->assertExecutionFailsWith($args, 'Failed to move the read cursor in the channel');
    }

    protected function createCommand()
    {
        return new ChannelsMarkCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:mark';
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
