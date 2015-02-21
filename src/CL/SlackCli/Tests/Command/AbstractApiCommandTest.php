<?php

namespace CL\SlackCli\Tests\Command;

abstract class AbstractApiCommandTest extends AbstractCommandTest
{
    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array_merge(parent::getDefaultOptions(), [
            'token',
        ]);
    }

    protected function getDefaultSuccessfulInput()
    {
        return array_merge(parent::getDefaultSuccessfulInput(), [
            '--token'   => 'testing-token',
            '--env'     => 'test-success',
            '--verbose' => 3,
        ]);
    }

    protected function getDefaultFailureInput()
    {
        return array_merge(parent::getDefaultFailureInput(), [
            '--token'   => 'testing-token',
            '--env'     => 'test-failure',
            '--verbose' => 3,
        ]);
    }
}
