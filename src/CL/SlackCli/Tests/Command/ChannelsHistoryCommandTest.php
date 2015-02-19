<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsHistoryCommand;

class ChannelsHistoryCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            '--oldest'   => '2014-01-01',
            '--latest'   => '2015-01-01',
            '--count'    => 123,
        ];

        $this->assertExecutionSucceedsWith($args, [
            'Successfully retrieved history',
            '+----------+---------+-----------+-----------+--------------+',
            '| ts       | type    | user      | username  | text         |',
            '+----------+---------+-----------+-----------+--------------+',
            '| 12345678 | message | U1234567! | acme_user | Hello world! |',
            '+----------+---------+-----------+-----------+--------------+',
        ]);

        $this->assertExecutionFailsWith($args, 'Failed to retrieve history for this channel');
    }

    protected function createCommand()
    {
        return new ChannelsHistoryCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:history';
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
        return [
            'latest',
            'oldest',
            'count',
        ];
    }
}
