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

use Nekland\Bundle\BaseAdminBundle\Crud\Entity\CrudableInterface;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Form\FormTypeInterface,
    Symfony\Component\Form\FormFactory,
    Symfony\Component\Form\Form;

use Doctrine\ORM\EntityManager;

class Handler
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    public function __construct(EntityManager $em, FormFactory $formFactory)
    {
        $this->em          = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * @param  FormTypeInterface                          $type
     * @param  CrudableInterface                          $object
     * @param  string                                     $action
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm(FormTypeInterface $type, $object, $action)
    {
        return $this->formFactory->create($type, $object, array(
            'method' => 'POST',
            'action' => $action
        ));
    }

    /**
     * @param Form $form
     * @param Request $request
     * @return bool
     */
    public function create(Form $form, Request $request, array $options=array())
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->executeCreate($form, $options);

            return true;
        }

        return false;
    }

    /**
     * @param Form $form
     */
    protected function executeCreate(Form $form, $options)
    {
        $this->em->persist($form->getData());
        $this->em->flush();
    }

    /**
     * @param Form $form
     * @param Request $request
     * @param array $options
     * @return bool
     */
    public function update(Form $form, Request $request, $options = array())
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->executeUpdate($form, $options);

            return true;
        }

        return false;
    }

    /**
     * @param Form $form
     */
    protected function executeUpdate(Form $form, $options)
    {
        $this->em->flush();
    }

}
