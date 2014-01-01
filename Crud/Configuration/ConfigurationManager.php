<?php
/**
 * Author: nek
 * Date: 01/01/14
 * Copyleft Nekland
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Configuration;


use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationManager
{
    /**
     * @var array
     */
    private $config;

    public function __construct()
    {
        $configuration = new Configuration();
        $this->config = $this->processConfiguration($configuration, $configs);
    }

    private function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new \Symfony\Component\Config\Definition\Processor();

        return $processor->processConfiguration($configuration, $configs);
    }
}
