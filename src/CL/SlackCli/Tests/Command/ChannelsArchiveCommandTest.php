<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsArchiveCommand;

class ChannelsArchiveCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully archived channel');
        $this->assertExecutionFailsWith($args, 'Failed to archive channel');
    }

    protected function createCommand()
    {
        return new ChannelsArchiveCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:archive';
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
