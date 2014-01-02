<?php
/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud;
use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Manager
 *
 * Manage administration and classes around configuration
 */
class Manager
{
    /**
     * @var ConfigurationManager
     */
    private $configuration;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel, ConfigurationManager $configManager)
    {
        $this->kernel        = $kernel;
        $this->configuration = $configManager;
    }

    public function loadConfiguration()
    {
        // Get bundles paths
        $bundles       = $this->kernel->getBundles();
        $paths         = array();
        $pathInBundles = 'Resources' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'nekland_admin.yml';

        foreach ($bundles as $bundle) {
            $paths[] = $bundle->getPath() . DIRECTORY_SEPARATOR . $pathInBundles;
        }
        $this->configuration->setPaths($paths);
        $this->configuration->loadConfigFiles();
    }

    public function createRoutes()
    {
        $routes = new RouteCollection();

        if (!$this->configuration->isReady()) {
            $this->loadConfiguration();
        }

        return $routes;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }
}
