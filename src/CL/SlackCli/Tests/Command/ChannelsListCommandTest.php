<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\AbstractCommand;
use CL\SlackCli\Command\ChannelsListCommand;

class ChannelsListCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([], [
            'Received 1 channels...',
            '+----------+--------------+--------------------------+----------+-------------------------------------------------------------------------------------------------------------------------------------------------+-------------------+--------------+---------------------------------------------------+---------------------------------------------------+',
            '| id       | name         | created                  | creator  | latest                                                                                                                                          | last_read         | members      | topic                                             | purpose                                           |',
            '+----------+--------------+--------------------------+----------+-------------------------------------------------------------------------------------------------------------------------------------------------+-------------------+--------------+---------------------------------------------------+---------------------------------------------------+',
            '| C1234567 | acme_channel | 1970-05-23T21:21:18+0000 | U1234567 | {"ts":"12345678.12345678","type":"message","channel":{"id":"C1234567","name":"#foo"},"user":"U1234567","username":"Acme","text":"Hello world!"} | 12345678.12345678 | ["U1234567"] | Discuss secret plans that no-one else should know | Discuss secret plans that no-one else should know |',
            '+----------+--------------+--------------------------+----------+-------------------------------------------------------------------------------------------------------------------------------------------------+-------------------+--------------+---------------------------------------------------+---------------------------------------------------+',
        ]);

        $this->assertExecutionFailsWith([], 'Failed to list channels');
    }

    /**
     * @return AbstractCommand
     */
    protected function createCommand()
    {
        return new ChannelsListCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'channels:list';
    }

    /**
     * @return array
     */
    protected function getExpectedArguments()
    {
        return [];
    }

    /**
     * @return array
     */
    protected function getExpectedOptions()
    {
        return [
            'exclude-archived',
        ];
    }
}
