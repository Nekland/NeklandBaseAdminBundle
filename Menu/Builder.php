<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nek
 * Date: 26/07/13
 * Time: 10:12
 * To change this template use File | Settings | File Templates.
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
