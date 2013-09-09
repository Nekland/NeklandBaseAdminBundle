<?php
/**
 * Author: nek
 * Date: 29/08/13
 * Copyleft Nekland
 */

namespace Nekland\Bundle\BaseAdminBundle\Form\Type;

use Exercise\HTMLPurifierBundle\Form\HTMLPurifierTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class NeklandEditorType
 *
 * Allow users to use nekland editor easily
 * The template is defined in "NeklandBaseAdminBundle::form.html.twig"
 *
 * @package Nekland\Bundle\BaseAdminBundle\Form\Type
 */
class NeklandEditorType extends AbstractType
{
    /**
     * @var HTMLPurifierTransformer
     */
    protected $purifierTransformer;

    public function __construct(HTMLPurifierTransformer $transformer)
    {
        $this->purifierTransformer = $transformer;
    }

    /**
     * Add transformer
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->addModelTransformer($this->purifierTransformer);
    }

    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'nekland_editor';
    }
}