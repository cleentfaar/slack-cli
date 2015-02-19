<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsKickCommand;

class ChannelsKickCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'user-id'    => 'U1234567',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully kicked user from the channel');
        $this->assertExecutionFailsWith($args, 'Failed to kick user from the channel');
    }

    protected function createCommand()
    {
        return new ChannelsKickCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:kick';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
            'user-id',
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
