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

/**
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class Config
{
    public static $defaultConfig = [
        'default_token' => null,
    ];

    private $config;
    private $repositories;
    private $configSource;
    private $useEnvironment;

    /**
     * @param boolean $useEnvironment Use SLACK_CLI_ environment variables to replace config settings
     */
    public function __construct($useEnvironment = true)
    {
        // load defaults
        $this->config         = static::$defaultConfig;
        $this->useEnvironment = (bool) $useEnvironment;
    }

    /**
     * @param $source
     */
    public function setConfigSource($source)
    {
        $this->configSource = $source;
    }

    /**
     * @return mixed
     */
    public function getConfigSource()
    {
        return $this->configSource;
    }

    /**
     * Merges new config values with the existing ones (overriding)
     *
     * @param array $config
     */
    public function merge($config)
    {
        // override defaults with given config
        if (!empty($config['config']) && is_array($config['config'])) {
            foreach ($config['config'] as $key => $val) {
                $this->config[$key] = $val;
            }
        }
    }

    /**
     * @return array
     */
    public function getRepositories()
    {
        return $this->repositories;
    }

    /**
     * Returns a setting
     *
     * @param string $key
     *
     * @throws \RuntimeException
     *
     * @return mixed
     */
    public function get($key)
    {
        switch ($key) {
            case 'home':
                return rtrim($this->process($this->config[$key]), '/\\');
            default:
                if (!isset($this->config[$key])) {
                    return null;
                }

                return $this->process($this->config[$key]);
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        $all = [];
        foreach (array_keys($this->config) as $key) {
            $all['config'][$key] = $this->get($key);
        }

        return $all;
    }

    /**
     * @return array
     */
    public function raw()
    {
        return [
            'config' => $this->config,
        ];
    }

    /**
     * Checks whether a setting exists
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Replaces {$refs} inside a config string
     *
     * @param string $value a config string that can contain {$refs-to-other-config}
     *
     * @return string
     */
    private function process($value)
    {
        $config = $this;

        if (!is_string($value)) {
            return $value;
        }

        return preg_replace_callback('#\{\$(.+)\}#', function ($match) use ($config) {
            return $config->get($match[1]);
        }, $value);
    }
}
