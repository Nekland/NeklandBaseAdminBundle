<?php

namespace Nekland\Bundle\BaseAdminBundle\Crud\Configuration;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree builder.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder The tree builder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nekland_admin');

        $rootNode
            ->children()
            ->scalarNode('dashboard')->defaultTrue()->end()
            ->end();

        return $treeBuilder;
    }
} 
