<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Controller;


use Nekland\Bundle\BaseAdminBundle\Crud\Exception\UnsupportedOptionException;
use Nekland\Bundle\BaseAdminBundle\Crud\Form\Handler;
use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AbstractCrudController
 *
 * Basic of crud, you can inherit from this class to create your own crud controller
 */
abstract class AbstractCrudController extends Controller
{
    /**
     * @var Resource
     */
    protected $resource;

    /**
     * List a resource in a table
     *
     * @param Request $request
     */
    public function indexAction()
    {
        $objects = $this->getRepository()->findAll();

        //var_dump($this->getResource()); exit;

        return $this->render($this->getResource()->getTemplate('index'), array(
            'objects'  => $objects,
            'resource' => $this->getResource()
        ));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id)
    {
        $object   = $this->getRepository()->find($id);
        $resource = $this->getResource();
        $route    = $resource->getRoute('update');
        $form     = $this->getForm(
            $object,
            $this->generateUrl(
                $route['name'],
                array('id' => $object->getId(), 'resource' => $resource->getSlug())
            )
        );

        return $this->render($this->getResource()->getTemplate('edit'), array(
            'object' => $object,
            'form'   => $form->createView(),
            'resource' => $resource
        ));
    }

    /**
     * Get the repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->getResource()->getModel());
    }

    /**
     * @param Resource $resource
     * @return AbstractCrudController
     */
    public function setResource(Resource $resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource
     */
    protected function getResource()
    {
        return $this->resource;
    }

    /**
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Form\Handler
     */
    protected function getFormHandler()
    {
        return new Handler($this->getDoctrine()->getManager(), $this->get('form.factory'));
    }

    protected function getForm($object, $url)
    {
        $resource = $this->getResource();

        if ($resource->hasFormType()) {

            $type = $resource->getFormType();

            if (is_string($type)) {
                $type = $this->get($type);
            }

            return $this->getFormHandler()->getForm($type, $object, $url);
        }

        $formGenerator = $this->get('nekland_admin.crud.form.generator');

        return $formGenerator->generate($object, $url);
    }

    /**
     * @param string $option name of the option you want to get
     * @param string $category name of the category of your option
     *
     * @return mixed
     * @throws UnsupportedOptionException
     */
    protected function getOption($option, $category = null)
    {
        var_dump($this->options); exit;
        if (null === $category && !empty($this->options[$option])) {
            return $option;
        }

        if (!empty($option[$category][$option])) {
            return $option;
        }

        throw new UnsupportedOptionException(sprintf('The option %s does not exists.', $option));
    }
}
