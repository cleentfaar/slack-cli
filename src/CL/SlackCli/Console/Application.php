<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Console;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputOption;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class Application extends BaseApplication
{
    /**
     * @var array
     */
    private $availableEnvironments = [
        'prod',
        'test', // does the same as 'test-success'
        'test-success', // mocks a successful response
        'test-failure', // mocks a failed response
    ];

    /**
     * @var string
     */
    private $defaultEnvironment = 'prod';

    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(-1);

        parent::__construct('Slack CLI', $this->getReplacedVersion());
    }

    /**
     * {@inheritdoc}
     */
    public function getLongVersion()
    {
        return sprintf('%s by <comment>Cas Leentfaar</comment>', parent::getLongVersion());
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultInputDefinition()
    {
        $parentDefinition = parent::getDefaultInputDefinition();
        $parentDefinition->addOption(new InputOption(
            '--env',
            'e',
            InputOption::VALUE_REQUIRED,
            sprintf('The environment to run the Slack CLI under (%s)', implode('|', $this->availableEnvironments)),
            $this->defaultEnvironment
        ));

        return $parentDefinition;
    }

    /**
     * @return null|string
     */
    private function getReplacedVersion()
    {
        $version = '@git-version@';
        if ($version === '@' . 'git-version@') {
            return 'UNKNOWN';
        }

        return $version;
    }
}
