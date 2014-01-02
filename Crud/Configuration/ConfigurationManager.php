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
            $configs[] = $this->loader->load($path);
        }

        $this->checkConfiguration($configs);
    }

    public function checkConfiguration(array $configurations)
    {
        foreach ($configurations as $entry) {
            $configuration = new Configuration();
            $this->config = $this->processConfiguration($configuration, $entry);
        }
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new \Symfony\Component\Config\Definition\Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
