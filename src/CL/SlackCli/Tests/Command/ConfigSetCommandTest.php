<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ConfigSetCommand;

class ConfigSetCommandTest extends AbstractCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([
            'setting' => 'default_token',
            'value'   => 'fake-token',
        ], 'Successfully changed value of `default_token` to `fake-token`');

        $this->assertExecutionFailsWith([
            'setting' => 'unknown_setting',
            'value'   => 'some-value',
        ], 'There is no setting with that name in the configuration: `unknown_setting`');
    }

    /**
     * @return ConfigSetCommand
     */
    protected function createCommand()
    {
        return new ConfigSetCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'config:set';
    }

    /**
     * @return array
     */
    protected function getExpectedArguments()
    {
        return [
            'setting',
            'value',
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
