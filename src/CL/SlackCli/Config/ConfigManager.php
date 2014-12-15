<?php

namespace CL\SlackCli\Config;

use Symfony\Component\Yaml\Yaml;

class ConfigManager
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $config = [];

    /**
     * @param string|null $path Leave null to use the user's home-dir (~/.slack-cli) automatically
     */
    public function __construct($path = null)
    {
        $this->setPath($path);

        $content = file_get_contents($this->path);
        $yaml    = Yaml::parse($content);

        $this->config = array_merge($this->getDefaultConfig(), $yaml);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * @param string $key
     * @param string $value
     * @param bool   $autoSave
     */
    public function set($key, $value, $autoSave = true)
    {
        $this->assertKey($key);

        $this->config[$key] = $value;

        if ($autoSave === true) {
            $this->save();
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->config;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function get($key)
    {
        $this->assertKey($key);

        return $this->config[$key];
    }

    public function save()
    {
        $content = Yaml::dump($this->config);
        $fh      = fopen($this->path, 'w+');

        fwrite($fh, $content);
        fclose($fh);
    }

    /**
     * @return string
     */
    public static function getDefaultPath()
    {
        return sprintf('%s/%s', $_SERVER['HOME'], '.slack-cli/config.yml');
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function setPath($path)
    {
        if ($path === null) {
            $path = $this->getDefaultPath();
        }

        if (!file_exists($path)) {
            $baseDir = dirname($path);
            if (!is_dir($baseDir)) {
                if (!mkdir($baseDir, 0777, true)) {
                    throw new \RuntimeException(sprintf(
                        'Failed to create directory in "%s", try running this with higher privileges',
                        $baseDir
                    ));
                }
            }

            $skeletonContent = Yaml::dump($this->getDefaultConfig());
            $fh              = fopen($path, 'w+');
            if (!$fh) {
                throw new \RuntimeException(sprintf('Failed to create new configuration file in "%s"', $path));
            }
            fwrite($fh, $skeletonContent);
            fclose($fh);
        }

        $this->path = $path;
    }

    /**
     * @param string $key
     *
     * @throws \InvalidArgumentException If the given key does not exist in the current configuration
     */
    private function assertKey($key)
    {
        if (!array_key_exists($key, $this->config)) {
            throw new \InvalidArgumentException(sprintf(
                'There is no key in the configuration with this name: %s, available keys are: %s',
                $key,
                implode(', ', array_keys($this->config))
            ));
        }
    }

    /**
     * @return array
     */
    private function getDefaultConfig()
    {
        return [
            'default_token' => null,
        ];
    }

    /**
     * @return string
     */
    private function getSkeletonPath()
    {
        return __DIR__ . '/../Resources/skeleton/config.yml.skeleton';
    }
}
