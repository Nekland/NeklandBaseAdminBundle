<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Listener;

use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;
use Nekland\Bundle\BaseAdminBundle\Crud\Manager;
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Event\OnConfigureMenuEvent;
use Nekland\Bundle\BaseAdminBundle\Utils\String;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;


class MenuListener
{
    /**
     * @var \Nekland\Bundle\BaseAdminBundle\Crud\Manager
     */
    private $manager;

    /**
     * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    private $translator;

    public function __construct(Manager $manager, Translator $translator)
    {
        $this->manager = $manager;
        $this->translator    = $translator;
    }

    /**
     * This method will be executed when the menu will be build.
     *
     * @param OnConfigureMenuEvent $event
     */
    public function onConfigureMenu(OnConfigureMenuEvent $event)
    {
//        $menu = $event->getMenu('News');
//
//        $menu->addChild('Ajouter', array('route' => 'admin_news_new'));

        $resources = $this->manager->getConfiguration()->getResources();

        foreach($resources as $name => $resource) {
            /** @var \Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource $resource */
            $menu = $event->getMenu($this->translator->trans($resource->getName()));

            $menu->addChild('Nouveau', array(
                'route' => 'nekland_admin_crud_new',
                'routeParams' => array(
                    'name' => $name
                )
            ));
        }
    }
}