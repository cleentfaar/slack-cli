<?php

/*
 * This file is part of the slack-cli package.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\SlackCli\Config;

use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;

/**
 * @author Ryan Weaver <ryan@knplabs.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Igor Wiedler <igor@wiedler.ch>
 * @author Nils Adermann <naderman@naderman.de>
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ConfigFactory
{
    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    public static function getHomeDir()
    {
        $home = getenv('SLACK_CLI_HOME');
        if (!$home) {
            if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
                if (!getenv('APPDATA')) {
                    throw new \RuntimeException('The APPDATA or SLACK_CLI_HOME environment variable must be set for Slack CLI to run correctly');
                }
                $home = strtr(getenv('APPDATA'), '\\', '/') . '/SlackCli';
            } else {
                if (!getenv('HOME')) {
                    throw new \RuntimeException('The HOME or SLACK_CLI_HOME environment variable must be set for Slack CLI to run correctly');
                }
                $home = rtrim(getenv('HOME'), '/') . '/.slack-cli';
            }
        }

        return $home;
    }

    /**
     * @param string $path
     *
     * @return Config
     */
    public static function createConfig($path)
    {
        $config = new Config();

        // load global config
        $file = new JsonFile($path);
        if ($file->exists()) {
            $config->merge($file->read());
        }
        
        $config->setConfigSource(new JsonConfigSource($file));

        return $config;
    }
}
