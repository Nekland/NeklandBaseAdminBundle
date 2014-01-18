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

use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Crud\Routing\RouteGenerator;
use Nekland\Bundle\BaseAdminBundle\Utils\Utils;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationManager
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var \Nekland\Bundle\BaseAdminBundle\Crud\Routing\RouteGenerator
     */
    private $routeGenerator;

    /**
     * @var ConfigurationLoader
     */
    private $loader;

    public function __construct()
    {
        //$this->routeGenerator = $routeGenerator;
        $this->loader         = new ConfigurationLoader();
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

        $config = $this->checkConfiguration($configs);
        $config = $this->hydrateConfig($config);
        //$config = $this->generateMissingData($config);

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
     * @param array $configuration
     * @return array of Resource
     */
    public function hydrateConfig(array $configuration)
    {
        $new = array();

        if (!empty($configuration['resources'])) {
            foreach ($configuration['resources'] as $name => $resourceArray) {
                $resource = new Resource();

                foreach($resourceArray as $element => $value) {
                    $method = 'set' . ucfirst($element);
                    $resource->{$method}($value);
                }

                // Please change it in future versions to empty test
                // (not supported before PHP 5.5)
                if ($resource->getName() === null) {
                    $resource->setName(ucfirst($name));
                }

                $resource->setSlug($name);

                $new[$name] = $resource;
            }
            $configuration['resources'] = $new;
        }

        return $configuration;
    }

    public function generateMissingData(array $configuration)
    {
        // Check if routes exists & generate theme if not
        foreach ($configuration['resources'] as $resource)
        {
            if (!$resource->hasRoutes()) {
                $this->routeGenerator->generateRoutes($resource);
            }
        }

        return $configuration;
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

    public function getResource($element, $or=null)
    {
        return empty($this->config['resources'][$element]) ? $or : $this->config['resources'][$element];
    }
    public function getResources()
    {
        return $this->config['resources'];
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new \Symfony\Component\Config\Definition\Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
