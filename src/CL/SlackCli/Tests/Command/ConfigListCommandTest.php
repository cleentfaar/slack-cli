<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ConfigListCommand;
use CL\SlackCli\Command\ConfigSetCommand;

class ConfigListCommandTest extends AbstractCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([], [
            '+---------------+-------+',
            '| Key           | Value |',
            '+---------------+-------+',
            '| default_token |       |',
            '+---------------+-------+',
        ]);
    }

    /**
     * @return ConfigSetCommand
     */
    protected function createCommand()
    {
        return new ConfigListCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'config:list';
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
