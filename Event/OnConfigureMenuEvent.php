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

use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\Event;

class OnConfigureMenuEvent extends Event
{
    /**
     * Knp Menu Root node
     *
     * @var ItemInterface
     */
    private $menu;

    /**
     * @param ItemInterface $menu
     */
    public function __construct(ItemInterface $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Return a section of the menu.
     *
     * If the section is null, return the root node.
     *
     * @param  string|null   $section
     * @return ItemInterface
     */
    public function getMenu($section=null)
    {
        if (is_null($section)) {
            return $this->menu;
        }

        if (!isset($this->menu[$section])) {
            $this->menu->addChild($section);
            $this->menu[$section]->setAttribute('class', 'nav-header');
        }

        return $this->menu[$section];
    }
}
