<?php

namespace Nekland\Bundle\BaseAdminBundle\Tests\Form\Type;

use Symfony\Component\Form\Test\TypeTestCase;
use Nekland\Bundle\BaseAdminBundle\Form\Type\NeklandEditorType;
use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;

class NeklandEditorTypeTest extends TypeTestCase
{

	/**
	 * @var \Nekland\Bundle\BaseAdminBundle\Form\Type\NeklandEditorType
	 */
	protected $type;

    protected function setUp()
    {
        parent::setUp();

        // Load the type
		$this->type = new NeklandEditorType(new HTMLPurifierTransformer(new \HTMLPurifier));
    }


	public function testTransformer()
	{
		$form = $this->factory->create($this->type);

		$form->submit('<p>Hello world, is everything alright ?</p><p class="foo">Non valid <span>HTML</p></span>');

		$this->assertEquals(
			'<p>Hello world, is everything alright ?</p><p class="foo">Non valid <span>HTML</span></p>',
			$form->getData()
		);
	}
}