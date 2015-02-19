<?php

namespace CL\SlackCli\Tests\Command;

use CL\SlackCli\Command\ConfigEditCommand;

class ConfigEditCommandTest extends AbstractCommandTest
{
    public function testExecute()
    {
        putenv('SLACK_CONFIG_EDITOR=mock-editor');

        $this->assertExecutionSucceedsWith([
        ], sprintf('Editing `%s` using `mock-editor`...', $this->getConfigurationPath()));
    }

    /**
     * @return ConfigEditCommand
     */
    protected function createCommand()
    {
        return new ConfigEditCommand();
    }

    /**
     * @return string
     */
    protected function getExpectedName()
    {
        return 'config:edit';
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
