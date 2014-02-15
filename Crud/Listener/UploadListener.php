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


use Nekland\Bundle\BaseAdminBundle\Event\OnHydrateConfigurationEvent;

class UploadListener
{
    /**
     * Change the handler to the upload handler if detect uploadable fields
     *
     * @param OnHydrateConfigurationEvent $event
     */
    public function onHydrateConfiguration(OnHydrateConfigurationEvent $event)
    {
        $uploadableFields = array();
        $resource         = $event->getResource();

        foreach ($event->getResource()->getProperties() as $property) {
            if ($property->getFormType() === 'file') {
                $uploadableFields[] = $property->getName();
            }
        }

        if (!empty($uploadableFields)) {
            $handlerConf = $resource->getOptions('handler', array());
            $handlerConf['uploadable'] = $uploadableFields;
            $resource->setOption('handler', $handlerConf);
        }
    }
} 