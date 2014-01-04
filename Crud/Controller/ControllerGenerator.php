<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Controller;

use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationManager;

class ControllerGenerator
{
    public function __construct(ConfigurationManager $configuration)
    {
        $this->configuration = $configuration;
    }

    public function getControllers()
    {
        if (!$this->configuration->isReady()) {
            throw new \Exception('The configuration is not ready');
        }
        $resourcesList = $this->configuration->get('resources');

        var_dump($resourcesList); exit;

        foreach($resourcesList as $resource) {

        }
    }
} 