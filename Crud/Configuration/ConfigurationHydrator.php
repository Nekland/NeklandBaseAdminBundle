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


use Nekland\Bundle\BaseAdminBundle\Crud\Model\Property;
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Event\Events;
use Nekland\Bundle\BaseAdminBundle\Event\OnHydrateConfigurationEvent;
use Nekland\Bundle\BaseAdminBundle\Utils\Utils;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ConfigurationHydrator
 *
 * Use an array to hydrate a Resource object
 * Call events for options in properties
 */
class ConfigurationHydrator
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcher
     */
    private $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param string $name
     * @param array $resources to learn more about, see the Configuration class
     */
    public function createNewResource($name, array $resourceArray)
    {
        $resource = new Resource();

        foreach($resourceArray as $element => $value) {
            if ($element === 'properties') {
                $this->hydrateProperties($resource, $value);
            } else {
                $method = 'set' . Utils::camelize($element);
                $resource->{$method}($value);
            }
        }

        $resource->setSlug($name);

        // TODO: Please change it in future versions to empty test
        // (not supported before PHP 5.5)
        if ($resource->getName() === null) {
            $resource->setName(ucfirst($resource->getSlug()));
        }

        $this->dispatcher->dispatch(Events::onCrudHydratation, new OnHydrateConfigurationEvent($resource));

        return $resource;
    }

    /**
     * @param Resource $resource
     * @param array $value
     */
    protected function hydrateProperties(Resource $resource, array $config)
    {
        $properties = array();

        foreach ($config as $name => $element) {
            $property = new Property();

            foreach ($element as $nodeName => $nodeValue) {
                $method = 'set' . Utils::camelize($nodeName);
                $property->{$method}($nodeValue);
            }

            $property->setName($name);
            $properties[$name] = $property;
        }

        $resource->setProperties($properties);

        return $resource;
    }
}
