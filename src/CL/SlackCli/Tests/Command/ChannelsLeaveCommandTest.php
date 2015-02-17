<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsLeaveCommand;

class ChannelsLeaveCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
        ];

        $this->assertExecutionSucceedsWith($args, 'Could not leave channel; not in channel');
        $this->assertExecutionFailsWith($args, 'Failed to leave channel');
    }

    protected function createCommand()
    {
        return new ChannelsLeaveCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:leave';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
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
