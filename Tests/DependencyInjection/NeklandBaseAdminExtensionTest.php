<?php

namespace Nekland\Bundle\BaseAdminBundle\Tests\DependencyInjection;

use Nekland\Bundle\BaseAdminBundle\DependencyInjection\NeklandBaseAdminExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests if the global bundle is valid
 */
class NeklandBaseAdminExtensionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var NeklandBaseAdminExtension
	 */
	protected $extension;

	/**
	 * Setup this test class
	 */
	protected function setUp()
	{
		$this->extension = new NeklandBaseAdminExtension();
	}

	public function testLoad()
	{
		// Making a valid container
		$container = new ContainerBuilder;
		$container->setParameter('kernel.root_dir', '/foo/bar/path');

        $this->extension->load(array(), $container);

        $this->assertTrue($container->hasDefinition('nekland_admin.form.type.editor'));
        $this->assertTrue($container->hasDefinition('nekland_admin.form.transformer.html_purifier'));
	}
}