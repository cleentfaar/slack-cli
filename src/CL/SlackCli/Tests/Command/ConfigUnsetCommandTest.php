<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ConfigSetCommand;
use CL\SlackCli\Command\ConfigUnsetCommand;

class ConfigUnsetCommandTest extends AbstractCommandTest
{
    public function testExecute()
    {
        $this->assertExecutionSucceedsWith([
            'setting' => 'default_token',
        ], 'Setting with name `default_token` has been removed from the configuration');

        $this->assertExecutionSucceedsWith([
            'setting' => 'unknown_setting',
        ], 'No changes made; there is no setting defined with the name `unknown_setting`');
    }

    /**
     * @return ConfigSetCommand
     */
    protected function createCommand()
    {
        return new ConfigUnsetCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'config:unset';
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
