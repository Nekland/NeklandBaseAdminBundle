<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Form;

use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

interface HandlerInterface
{
    /**
     * @param  FormTypeInterface|string                   $type
     * @param  object                                     $object
     * @param  string                                     $action
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm($type, $object, $action);

    /**
     * @param Form    $form
     * @param Request $request
     * @param array   $options
     * @return bool
     */
    public function create(Form $form, Request $request, array $options=array());

    /**
     * @param Form    $form
     * @param Request $request
     * @param array   $options
     * @return bool
     */
    public function update(Form $form, Request $request, $options = array());

    /**
     * @param array $options
     */
    public function setOptions(array $options);
} 
