<?php

namespace CL\SlackCli\Config;

use Composer\Config\JsonConfigSource;
use Composer\Json\JsonFile;
use Composer\IO\IOInterface;

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
                $home = strtr(getenv('APPDATA'), '\\', '/') . '/Composer';
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
     * @param IOInterface|null $io
     *
     * @return Config
     */
    public static function createConfig(IOInterface $io = null)
    {
        // determine home and cache dirs
        $home = self::getHomeDir();

        // Protect directory against web access. Since HOME could be
        // the www-data's user home and be web-accessible it is a
        // potential security risk
        if (!file_exists($home . '/.htaccess')) {
            if (!is_dir($home)) {
                @mkdir($home, 0777, true);
            }
            @file_put_contents($home . '/.htaccess', 'Deny from all');
        }

        $config = new Config();

        // add dirs to the config
        $config->merge(array('config' => array('home' => $home)));

        // load global config
        $file = new JsonFile($home . '/config.json');
        if ($file->exists()) {
            if ($io && $io->isDebug()) {
                $io->write('Loading config file ' . $file->getPath());
            }
            $config->merge($file->read());
        }
        $config->setConfigSource(new JsonConfigSource($file));

        return $config;
    }
}
