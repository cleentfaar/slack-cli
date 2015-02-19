<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsSetTopicCommand;

class ChannelsSetTopicCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'topic'      => 'New topic',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully changed topic of channel to: "new_topic"');
        $this->assertExecutionFailsWith($args, 'Failed to change topic of channel');
    }

    protected function createCommand()
    {
        return new ChannelsSetTopicCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:set-topic';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
            'topic',
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
