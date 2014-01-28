<?php

namespace Nekland\Bundle\BaseAdminBundle\Crud\Form;


use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;

class UploadHandler extends Handler
{
    /**
     * @var string
     */
    private $uploadDir;

    /**
     * @param EntityManager $em
     * @param FormFactory $formFactory
     * @param string $uploadDir
     */
    public function __construct(EntityManager $em, FormFactory $formFactory, $uploadDir)
    {
        parent::__construct($em, $formFactory);
        $this->uploadDir = $uploadDir;
    }

    public function executeUpdate(Form $form)
    {
    }
}
