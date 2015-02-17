<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ConfigGetCommand;
use CL\SlackCli\Command\ConfigSetCommand;

class ConfigGetCommandTest extends AbstractCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([
            'setting' => 'default_token',
        ], 'Value of `default_token` is NULL');

        $this->assertExecutionFailsWith([
            'setting' => 'unknown_setting',
        ], 'There is no setting with that name in the configuration: `unknown_setting`');
    }

    /**
     * @return ConfigSetCommand
     */
    protected function createCommand()
    {
        return new ConfigGetCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'config:get';
    }

    /**
     * @return array
     */
    protected function getExpectedArguments()
    {
        return [
            'setting',
        ];
    }

    /**
     * @return array
     */
    protected function getExpectedOptions()
    {
        return [];
    }
}
