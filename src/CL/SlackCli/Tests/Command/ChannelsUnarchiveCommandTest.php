<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsUnarchiveCommand;

class ChannelsUnarchiveCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully un-archived channel');
        $this->assertExecutionFailsWith($args, 'Failed to un-archive channel');
    }

    protected function createCommand()
    {
        return new ChannelsUnarchiveCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:unarchive';
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
