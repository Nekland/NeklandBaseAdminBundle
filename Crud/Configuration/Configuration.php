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


use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

/**
 * Class Configuration
 * Notice: Any change here impact directly changes in the Resource model
 */
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

        $node = $rootNode
            ->children()
            ->arrayNode('resources')
                ->useAttributeAsKey('slug')
                ->prototype('array')
                    ->children()
                        // Name of the service to call
                        ->scalarNode('driver')->defaultValue('doctrine')->end()
                        ->scalarNode('manager')->defaultValue('default')->end()
                        ->scalarNode('name')->end()
                        ->scalarNode('pluralName')->end()
                        ->scalarNode('labelTranslation')->end()
                        ->arrayNode('classes')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('model')->isRequired()->cannotBeEmpty()->end()
                                ->scalarNode('controller')->defaultValue('Nekland\Bundle\BaseAdminBundle\Controller\CrudController')->end()
                                ->scalarNode('repository')->defaultNull()->end()
                                ->scalarNode('type')->defaultNull()->end()
                                ->scalarNode('handler')->defaultValue('nekland_admin.form.basic_handler')->end()
                            ->end()
                        ->end()
                        ->arrayNode('rights')
                            ->children()
                                ->scalarNode('delete')->defaultValue(true)->end()
                                ->scalarNode('edit')->defaultValue(true)->end()
                            ->end()
                        ->end()
                        ->arrayNode('templates')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('index')->defaultValue('NeklandBaseAdminBundle:Crud:index.html.twig')->end()
                                ->scalarNode('new')->defaultValue('NeklandBaseAdminBundle:Crud:new.html.twig')->end()
                                ->scalarNode('edit')->defaultValue('NeklandBaseAdminBundle:Crud:edit.html.twig')->end()
                                ->scalarNode('show')->defaultValue('NeklandBaseAdminBundle:Crud:show.html.twig')->end()
                                ->scalarNode('form')->defaultValue('NeklandBaseAdminBundle:Crud:form.html.twig')->end()
                            ->end()
                        ->end()
                        ->arrayNode('properties')
                        ->defaultValue(array('id' => array('name' => 'id', 'sortable' => false, 'displayed' => false, 'editable' => false, 'label' => 'N°')))
                            ->useAttributeAsKey('name')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('label')->end()
                                    ->booleanNode('sortable')->defaultFalse()->end()
                                    ->booleanNode('displayed')->defaultTrue()->end()
                                    ->scalarNode('block')->end()
                                    ->scalarNode('form_type')->end()
                                    ->booleanNode('editable')->defaultTrue()->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('routes')
                        ->addDefaultsIfNotSet()
                            ->children();

        foreach (array('index', 'new', 'create') as $routeName) {
            $node = $this->addRouteNode($node, $routeName);
        }
        foreach (array('edit', 'update', 'delete', 'show') as $routeName) {
            $node = $this->addRouteNode($node, $routeName, array('id'));
        }

        $node

                            ->end()
                        ->end()
                        ->arrayNode('actions')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('label')->isRequired()->end()
                                    ->arrayNode('route')
                                        ->children()
                                            ->scalarNode('name')->isRequired()->end()
                                            ->arrayNode('parameters')
                                                ->prototype('scalar')->end()
                                            ->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }

    /**
     * @param  NodeBuilder $node      Node where we need to add the config
     * @param  string      $routeName Name of the route
     * @param  array       $default   Array of default values
     * @return NodeBuilder
     */
    private function addRouteNode(NodeBuilder $node, $routeName,array $default = null)
    {
        $node = $node->arrayNode($routeName)
            ->addDefaultsIfNotSet()
                ->children()
                ->scalarNode('name')->defaultValue('nekland_base_admin_crud_' . $routeName)->end()
                ->arrayNode('parameters');

        if (null !== $default && is_array($default)) {
            $node = $node->defaultValue($default);
        }


        $node = $node
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ->end();

        return $node;
    }
} 
