<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Routing;

use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RouteGenerator
{
    public function __construct(ConfigurationManager $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getRoutes()
    {
        if (!$this->configuration->isReady()) {
            throw new \Exception('The configuration is not ready');
        }
        $resourcesList = $this->configuration->get('resources');

        $collection = new RouteCollection();

        foreach($resourcesList as $name => $resource) {
            $collection->add('nekland_admin_add_' . $name, $this->getNewRoute($name, $resource));
        }

        return $collection;
    }

    protected function getNewRoute($name, $resource)
    {
        return array(
            '/' . $name . '/new',
            array('_controller' => 'NeklandBaseAdminBundle:Crud:new'),
            array(
                '_method' => 'GET',
                'model'   => $resource['classes']['model'],
                'driver'  => $resource['classes']['driver'],
                'manager'  => $resource['classes']['manager']
            )
        );
    }

    public function generateRoutes(Resource $resource)
    {
        $baseName = mb_strtolower($resource->getName());

        $resource->addRoute($this->getNewRoute());
    }
} 