<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Configuration;

use Nekland\Bundle\BaseAdminBundle\Utils\Utils;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var array of strings
     */
    private $paths;

    /**
     * @var ConfigurationLoader
     */
    private $loader;

    public function __construct($locationInBundles=null, $filename='nekland_admin.yml')
    {
        if ($locationInBundles === null) {
            $locationInBundles = 'Resources'.DIRECTORY_SEPARATOR.'config';
        }
        $this->locationInBundles = $locationInBundles;
        $this->loader            = new ConfigurationLoader();
    }

    /**
     * @param string $paths
     */
    public function setPaths($paths)
    {
        $this->paths = $paths;

        return $this;
    }

    public function loadConfigFiles()
    {
        if (empty($this->paths)) {
            throw new ConfigurationException('Bundles paths are not set. Impossible to load the configuration.');
        }

        $configs = array();
        foreach ($this->paths as $path) {
            try {
                $configs[] = $this->loader->load($path);
            } catch (ConfigurationException $e) {}
        }

        $config = array();
        foreach ($configs as $element) {
            if (empty($config)) {
                $config = $element;
            } else {
                $config = Utils::array_merge_recursive($config, $element);
            }
        }
        $config = array($config);

/*
        $configsSort = array();
        foreach($configs as $key => $element) {
            var_dump($key);
            if ($key === 'nekland_admin') {
                $configsSort[] = $element;
            }
        }
*/
        $config = $this->checkConfiguration($configs);

        return $this->config = $config;
    }

    public function checkConfiguration(array $configurations)
    {
        $configSchema = new Configuration();
        $config       = array();

        foreach ($configurations as $entry) {
            $config = $this->processConfiguration($configSchema, $entry);
        }

        return $config;
    }

    /**
     * @return bool
     */
    public function isReady()
    {
        return $this->config !== null;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Get an element of the configuration
     * @param $element
     * @param mixed|null $or
     * @return mixed
     */
    public function get($element, $or = null)
    {
        return empty($this->config[$element]) ? $or : $this->config[$element];
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new \Symfony\Component\Config\Definition\Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
