<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsSetPurposeCommand;

class ChannelsSetPurposeCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'purpose'    => 'New purpose',
        ];

        $this->assertExecutionSucceedsWith($args, 'Successfully changed purpose of channel to: "new_purpose"');
        $this->assertExecutionFailsWith($args, 'Failed to change purpose of channel');
    }

    protected function createCommand()
    {
        return new ChannelsSetPurposeCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:set-purpose';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
            'purpose',
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
