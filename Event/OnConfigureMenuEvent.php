<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nek
 * Date: 26/07/13
 * Time: 10:21
 * To change this template use File | Settings | File Templates.
 */

namespace Nekland\Bundle\BaseAdminBundle\Event;


use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class OnConfigureMenuEvent extends Event
{
    /**
     * @var ItemInterface
     */
    private $menu;

    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }
}