<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsInviteCommand;

class ChannelsInviteCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'user-id'    => 'U1234567',
        ];

        $this->assertExecutionSucceedsWith($args, [
            'Successfully invited user to the channel',
            'Channel used:',
            'C1234567',
            'acme_channel',
            '1970-05-23T21:21:18+0000',
            'U1234567',
            '{"ts":"12345678.12345678","type":"message","channel":{"id":"C1234567","name":"#foo"},"user":"U1234567","username":"Acme","text":"Hello world!","attachments":[]}',
            '12345678.12345678',
            '["U1234567"]',
            '{"value":"Discuss secret plans that no-one else should know","type":"text","creator":"U024BE7LH","last_set":"2013-02-13T19:13:24+0000"}',
            '{"value":"Discuss secret plans that no-one else should know","type":"text","creator":"U024BE7LH","last_set":"2013-02-13T19:13:24+0000"}',
        ]);

        $this->assertExecutionFailsWith($args, 'Failed to invite user to this channel');
    }

    protected function createCommand()
    {
        return new ChannelsInviteCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:invite';
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
