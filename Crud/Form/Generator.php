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
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Form\DataTransformer\StringToFileTransformer;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Translation\TranslatorInterface;

class Generator
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @var \Symfony\Component\Translation\Translator
     */
    private $translator;

    /**
     * @var \Nekland\Bundle\BaseAdminBundle\Form\DataTransformer\StringToFileTransformer
     */
    private $fileTransformer;

    /**
     * @param Manager                 $manager
     * @param FormFactory             $factory
     * @param TranslatorInterface              $translator
     * @param StringToFileTransformer $fileTransformer
     */
    public function __construct(Manager $manager, FormFactory $factory, TranslatorInterface $translator, StringToFileTransformer $fileTransformer)
    {
        $this->manager         = $manager;
        $this->formFactory     = $factory;
        $this->translator      = $translator;
        $this->fileTransformer = $fileTransformer;
    }

    /**
     * Generate a form using an entity/object
     *
     * @param object $entity
     * @param \Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource $resource
     * @param string $url
     * @param string $method
     *
     * @return \Symfony\Component\Form\Form
     */
    public function generate($entity, Resource $resource, $url, $method='POST')
    {
        $builder        = $this->formFactory->createBuilder('form', $entity);
        $properties     = $resource->getProperties();
        $defaultOptions = ['required' => false];

        foreach ($properties as $property) {
            if ($property->getEditable()) {
                $label = $property->getLabel();
                if (!empty($label)) {
                    $defaultOptions['label'] = $this->translator->trans($label);
                }

                $formType = $property->getFormType();

                if (empty($formType)) {
                    $builder->add($property->getName(), null, $defaultOptions);
                } else {
                    if ($formType === 'file') {
                        $builder->add(
                            $builder
                                ->create($property->getName(), $formType)
                                ->addModelTransformer($this->fileTransformer)
                        );
                    } else {
                        $builder->add($property->getName(), $formType, $defaultOptions);
                    }
                }
            }
        }

        $builder
            ->add('save', 'submit', ['label' => 'save'])
            ->setMethod($method)
            ->setAction($url);

        return $builder->getForm();
    }
}
