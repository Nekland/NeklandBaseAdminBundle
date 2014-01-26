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


use Nekland\Bundle\BaseAdminBundle\Crud\Manager;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Form\FormFactory;

class Generator
{
    /**
     * @var Manager
     */
    private $manager;

    public function __construct(Manager $manager, Reader $reader, FormFactory $factory)
    {
        $this->manager          = $manager;
        $this->annotationReader = $reader;
        $this->formFactory      = $factory;
    }

    /**
     * Generate a form using an entity/object
     *
     * @param object $entity
     * @return \Symfony\Component\Form\Form
     */
    public function generate($entity, $url, $method='POST')
    {
        $reflection = new \ReflectionClass($entity);
        $builder    = $this->formFactory->createBuilder('form', $entity);

        foreach ($reflection->getProperties() as $property) {
            $builder->add($property->getName());
        }

        $builder
            ->add('save', 'submit', array('label' => 'save'))
            ->setMethod($method)
            ->setAction($url);

        return $builder->getForm();
    }
}
