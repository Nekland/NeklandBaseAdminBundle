<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AfterCreateEvent extends Event
{
    private $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    public function getObject()
    {
        return $this->object;
    }
}
