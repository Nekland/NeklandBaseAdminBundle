<?php
/**
 * Author: nek
 * Date: 29/08/13
 * Copyleft Nekland
 */

namespace Nekland\Bundle\BaseAdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function getParent()
    {
        return 'textarea';
    }

    public function getName()
    {
        return 'nekland_editor';
    }
}