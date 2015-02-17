<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsCreateCommand;

class ChannelsCreateCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'name' => 'My new channel',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully created channel');
        $this->assertExecutionFailsWith($args, 'Failed to create channel');
    }

    protected function createCommand()
    {
        return new ChannelsCreateCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:create';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'name',
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
