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

class Handler extends AbstractCrudHandler
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(FormFactory $formFactory, Generator $generator, EntityManager $em)
    {
        parent::__construct($formFactory, $generator);
        $this->em          = $em;
    }

    /**
     * @param Form $form
     */
    protected function executeCreate(Form $form, array $options)
    {
        $this->em->persist($form->getData());
        $this->em->flush();
    }

    /**
     * @param Form $form
     */
    protected function executeUpdate(Form $form, array $options)
    {
        $this->em->flush();
    }

}
