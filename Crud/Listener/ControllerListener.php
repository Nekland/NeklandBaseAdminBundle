<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Listener;


use Nekland\Bundle\BaseAdminBundle\Controller\CrudController;
use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ControllerListener
{
    private $configuration;

    public function __construct(ConfigurationManager $configuration)
    {
        $this->configuration = $configuration;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if ($controller instanceof CrudController) {
            $controller->setConfiguration($this->configuration->getResource(''));
        }
    }
} 