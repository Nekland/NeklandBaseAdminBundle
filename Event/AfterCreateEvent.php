<?php
/**
 * User: nek
 * Date: 01/07/13
 * Geek Otera Project
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