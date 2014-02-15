<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Tests\Crud\Configuration;

use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationLoader;


class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase
{
    private $loader;
    /**
     * Setup this test class
     */
    protected function setUp()
    {
        $this->loader = new ConfigurationLoader();
    }

    public function testYamlLoad()
    {
        $array = $this->loader->load(__DIR__ . '/../../Data/config.yml');

        $this->assertEquals($array['nekland_admin']['resources']['user']['classes']['model'], 'App\Entity\User');
    }
}