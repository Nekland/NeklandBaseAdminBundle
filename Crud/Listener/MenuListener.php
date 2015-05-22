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

use Nekland\Bundle\BaseAdminBundle\Crud\Manager;
use Nekland\Bundle\BaseAdminBundle\Event\OnConfigureMenuEvent;
use Symfony\Component\Translation\TranslatorInterface;


class MenuListener
{
    /**
     * @var \Nekland\Bundle\BaseAdminBundle\Crud\Manager
     */
    private $manager;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(Manager $manager, TranslatorInterface $translator)
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
        $resources = $this->manager->getConfiguration()->getResources();

        foreach($resources as $name => $resource) {
            /** @var \Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource $resource */
            $menu = $event->getMenu(ucfirst($this->translator->trans($resource->getPluralName())));

            $menu->addChild('Lister', [
                'route' => 'nekland_base_admin_crud_index',
                'routeParameters' => [
                    'resource' => $name
                ]
            ]);

            $menu->addChild('Nouveau', [
                'route' => 'nekland_base_admin_crud_new',
                'routeParameters' => [
                    'resource' => $name
                ]
            ]);
        }
    }
}
