<?php

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
    public function getForm(FormTypeInterface $type, CrudableInterface $object, $action)
    {
        return $this->formFactory->create($type, $object, array(
            'method' => 'POST',
            'action' => $action
        ));
    }

    public function create(Form $form, Request $request)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->em->persist($form->getData());
            $this->em->flush();

            return true;
        }

        return false;
    }

    public function update(Form $form, Request $request)
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->em->flush();

            return true;
        }

        return false;
    }

}
