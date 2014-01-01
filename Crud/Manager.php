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

/**
 * Class Manager
 *
 * Manage administration and classes around configuration
 */
class Manager
{
    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->configuration = new ConfigurationManager();
    }
}
