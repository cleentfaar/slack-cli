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
        return array_merge(parent::getDefaultSuccessfulInput(), [
            '--token' => 'testing-token',
            '--env'   => 'test-success',
        ]);
    }

    protected function getDefaultFailureInput()
    {
        return array_merge(parent::getDefaultFailureInput(), [
            '--token' => 'testing-token',
            '--env'   => 'test-failure',
        ]);
    }
}
