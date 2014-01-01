<?php

namespace Nekland\Bundle\BaseAdminBundle\Routing;


use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdminLoader extends Loader
{
    public function __construct()
    {

    }

    /**
     * Loads a resource.
     *
     * @param mixed $resource The resource
     * @param string $type The resource type
     */
    public function load($resource, $type = null)
    {
        $routes = $this->manager->createRoutes();



        return $routes;
    }

    /**
     * Returns true if this class supports the given resource.
     *
     * @param mixed $resource A resource
     * @param string $type The resource type
     *
     * @return Boolean true if this class supports the given resource, false otherwise
     */
    public function supports($resource, $type = null)
    {
        return $type === 'neklandadmin';
    }

} 
