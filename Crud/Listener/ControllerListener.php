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
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ControllerListener
 *
 * Inject options for crud controllers
 */
class ControllerListener
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Nekland\Bundle\BaseAdminBundle\Crud\Manager
     */
    private $manager;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->manager   = $container->get('nekland_admin.crud.manager');
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $controllerConfig = $event->getController();
        $controller       = $controllerConfig[0];

        if ($controller instanceof AbstractCrudController) {
            // TODO: Change the controller if the controller is changed in configuration
            $request = $event->getRequest();
            if ($request->attributes->has('resource')) {

                $resource = $this->manager->getConfiguration()->getResource($request->attributes->get('resource'));

                if (empty($resource)) {
                    throw new NotFoundHttpException('La resource n\'existe pas ou n\'est pas configurée');
                }

                $classes = $resource->getClasses();
                if ($classes['controller'] !== get_class($controller)) {
                    $controllerClass = $classes['controller'];
                    $controller = new $controllerClass();

                    if($controller instanceof \Symfony\Component\DependencyInjection\ContainerAware) {
                        $controller->setContainer($this->container);
                    }
                    $event->setController(array($controller, $controllerConfig[1]));
                }

                $controller->setResource($resource);
            }
        }
    }
} 
