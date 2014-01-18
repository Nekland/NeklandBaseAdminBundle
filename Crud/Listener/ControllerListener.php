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


use Nekland\Bundle\BaseAdminBundle\Controller\AbstractCrudController;
use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;
use Nekland\Bundle\BaseAdminBundle\Crud\Manager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ControllerListener
 *
 * Inject options for crud controllers
 */
class ControllerListener
{
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerConfig = $event->getController();
        $controller       = $controllerConfig[0];

        if ($controller instanceof AbstractCrudController) {
            $request = $event->getRequest();
            if ($request->attributes->has('resource')) {

                $resource = $this->manager->getConfiguration()->getResource($request->attributes->get('resource'));

                if (empty($resource)) {
                    throw new NotFoundHttpException('La resource n\'existe pas ou n\'est pas configurée');
                }

                $controller->setResource($resource);
            }


        }
    }
} 
