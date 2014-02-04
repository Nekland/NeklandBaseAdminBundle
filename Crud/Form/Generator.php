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
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Form\DataTransformer\StringToFileTransformer;
use Symfony\Component\Form\FormFactory;

class Generator
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @param Manager $manager
     * @param Reader $reader
     * @param FormFactory $factory
     */
    public function __construct(Manager $manager, Reader $reader, FormFactory $factory, StringToFileTransformer $transformer)
    {
        $this->manager          = $manager;
        $this->annotationReader = $reader;
        $this->formFactory      = $factory;
        $this->fileTransformer  = $transformer;
    }

    /**
     * Generate a form using an entity/object
     *
     * @param object $entity
     * @return \Symfony\Component\Form\Form
     */
    public function generate($entity, Resource $resource, $url, $method='POST')
    {
        $builder    = $this->formFactory->createBuilder('form', $entity);
        $properties = $resource->getProperties();
        $defaultOptions = array('required' => false);

        foreach ($properties as $property) {
            if ($property->getEditable()) {
                $formType = $property->getFormType();

                if (empty($formType)) {
                    $builder->add($property->getName(), null, $defaultOptions);
                } else {
                    if ($formType === 'file') {
                        $builder->add(
                            $builder->create($property->getName(), $formType)
                                ->addModelTransformer($this->fileTransformer)
                        );
                    } else {
                        $builder->add($property->getName(), $formType, $defaultOptions);
                    }
                }
            }
        }

        $builder
            ->add('save', 'submit', array('label' => 'save'))
            ->setMethod($method)
            ->setAction($url);

        return $builder->getForm();
    }
}
