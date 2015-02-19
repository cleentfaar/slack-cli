<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\AbstractCommand;
use CL\SlackCli\Command\AuthTestCommand;

class AuthTestCommandTest extends AbstractApiCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([], 'Successfully authenticated by the Slack API');
        $this->assertExecutionFailsWith([], 'Failed to be authenticated by the Slack API');
    }

    /**
     * @return AbstractCommand
     */
    protected function createCommand()
    {
        return new AuthTestCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'auth:test';
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
        return [];
    }
}
