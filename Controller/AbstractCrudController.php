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
     * @return Resource
     */
    protected function getResource()
    {
        return $this->resource;
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
