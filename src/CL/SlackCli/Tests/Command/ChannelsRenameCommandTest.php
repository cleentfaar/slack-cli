<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ChannelsRenameCommand;

class ChannelsRenameCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $args = [
            'channel-id' => 'C1234567',
            'name'       => 'New name',
        ];

        $this->assertExecutionSucceedsWith($args, [
            'Successfully renamed channel!',
            'Renamed channel:',
            '+-----------+-------------------------------------------------------------------------------------------------------------------------------------------------+',
            '| id        | C1234567                                                                                                                                        |',
            '| name      | acme_channel                                                                                                                                    |',
            '| created   | 1970-05-23T21:21:18+0000                                                                                                                        |',
            '| creator   | U1234567                                                                                                                                        |',
            '| latest    | {"ts":"12345678.12345678","type":"message","channel":{"id":"C1234567","name":"#foo"},"user":"U1234567","username":"Acme","text":"Hello world!"} |',
            '| last_read | 12345678.12345678                                                                                                                               |',
            '| members   | ["U1234567"]                                                                                                                                    |',
            '| topic     | {"value":"Discuss secret plans that no-one else should know","type":"text","creator":"U024BE7LH","last_set":"2013-02-13T19:13:24+0000"}         |',
            '| purpose   | {"value":"Discuss secret plans that no-one else should know","type":"text","creator":"U024BE7LH","last_set":"2013-02-13T19:13:24+0000"}         |',
            '+-----------+-------------------------------------------------------------------------------------------------------------------------------------------------+',
        ]);

        $this->assertExecutionFailsWith($args, 'Failed to rename channel');
    }

    protected function createCommand()
    {
        return new ChannelsRenameCommand();
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedName()
    {
        return 'channels:rename';
    }

    /**
     * {@inheritdoc}
     */
    protected function getExpectedArguments()
    {
        return [
            'channel-id',
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
