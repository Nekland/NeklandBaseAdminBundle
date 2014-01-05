<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class NeklandBaseAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Configure other bundles
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');


        // Configure LiipImagineBundle if available
        if (isset($bundles['LiipImagineBundle'])) {

            $config = array(
                'filter_sets' => array(
                    'nekland_admin_thumb' => array(
                        'quality' => 75,
                        'filters' => array(
                            'thumbnail' => array(
                                'size' => array(150, 150),
                                'mode' => 'inset'
                            )
                        )
                    )
                )
            );
            $container->prependExtensionConfig('liip_imagine', $config);
        }
    }
}
