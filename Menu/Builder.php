<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Nekland\Bundle\BaseAdminBundle\Event\Events;
use Nekland\Bundle\BaseAdminBundle\Event\OnConfigureMenuEvent;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->setAttribute('class', 'nav nav-list');

        $menu->addChild('Accueil', array(
            'route' => 'nekland_base_admin_homepage',
            'attributes' => array('icon' => 'icon-home')
        ));

        $this->container->get('event_dispatcher')->dispatch(
            Events::onConfigureMenu,
            new OnConfigureMenuEvent($menu)
        );

        return $menu;
    }
}
