<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Tests\Crud\Configuratio;

use Nekland\Bundle\BaseAdminBundle\Crud\Configuration\ConfigurationHydrator;


class ConfigurationHydratorTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Setup this test class
	 */
	protected function setUp()
	{
		$eventDispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');
		$eventDispatcher->expects($this->any())
			->method('dispatch');

		$this->hydrator = new ConfigurationHydrator($eventDispatcher);
	}

	public function testCreateNewResource()
	{
		$resource = $this->hydrator->createNewResource('user', $this->getConfig());

		$this->assertEquals('User', $resource->getName());
	}

	protected function getConfig()
	{
		return array(
			'slug'    => 'user'
		);
	}
} 