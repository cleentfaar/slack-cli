<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\AbstractCommand;
use CL\SlackCli\Command\ApiTestCommand;

class ApiTestCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith(
            [],
            'Slack API seems to have responded correctly'
        );

        $this->assertExecutionSucceedsWith([
            '--arguments' => [
                'foo:bar',
            ],
        ], 'Slack API seems to have responded correctly');
        $this->assertExecutionSucceedsWith([
            '--arguments' => [
                'foo:bar',
            ],
            '--error' => 'some expected error',
        ], 'Slack API seems to have responded correctly');

        $this->assertExecutionFailsWith([], 'Slack API did not respond correctly (no error expected)');
    }

    /**
     * @return AbstractCommand
     */
    protected function createCommand()
    {
        return new ApiTestCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'api:test';
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
            'arguments',
            'error',
        ];
    }
}
