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
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class ConfigurationHydrator
 *
 * Use an array to hydrate a Resource object
 * Call events for options in properties
 */
class ConfigurationHydrator
{
    const config_option_hydrate_event = 'nekland_config_option_hydrate';

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
                $method = 'set' . ucfirst($element);
                $resource->{$method}($value);
            }
        }

        $resource->setSlug($name);

        // Please change it in future versions to empty test
        // (not supported before PHP 5.5)
        if ($resource->getName() === null) {
            $resource->setName(ucfirst($resource->getSlug()));
        }

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
                $method = 'set' . ucfirst($nodeName);
                $property->{$method}($nodeValue);
            }

            $property->setName($name);
            $properties[$name] = $property;
        }

        $resource->setProperties($properties);

        return $resource;
    }

    /**
     * Uses the event dispatcher to get options
     *
     * @param Resource $resource
     * @param array $config
     */
    protected function hydrateOption(Resource $resource, array $config)
    {
        // TODO
    }
}
