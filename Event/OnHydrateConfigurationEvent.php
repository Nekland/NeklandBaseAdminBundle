<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Event;


use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Symfony\Component\EventDispatcher\Event;

class OnHydrateConfigurationEvent extends Event
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @param Resource $resource
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @return Resource
     */
    public function getResource()
    {
        return $this->resource;
    }
} 