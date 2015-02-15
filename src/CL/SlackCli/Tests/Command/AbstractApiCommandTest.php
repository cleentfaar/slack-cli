<?php

namespace CL\SlackCli\Tests\Command;

abstract class AbstractApiCommandTest extends AbstractCommandTest
{
    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return [
            'token',
            'configuration-path',
        ];
    }

    protected function getDefaultSuccessfulInput()
    {
        return [
            '--token' => 'testing-token',
            '--env'   => 'test-success',
        ];
    }

    protected function getDefaultFailureInput()
    {
        return [
            '--token' => 'testing-token',
            '--env'   => 'test-failure',
        ];
    }
}
