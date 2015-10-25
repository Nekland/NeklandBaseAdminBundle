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

use Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource;
use Nekland\Bundle\BaseAdminBundle\Event\AfterCreateEvent;
use Nekland\Bundle\BaseAdminBundle\Event\AfterUpdateEvent;
use Nekland\Bundle\BaseAdminBundle\Event\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\CssSelector\Parser\Handler\HandlerInterface;
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
     * @var HandlerInterface
     */
    private $handler;

    /**
     * List a resource in a table
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $objects = $this->getRepository()->findAll();

        return $this->render($this->getResource()->getTemplate('index'), array(
            'objects'  => $objects,
            'resource' => $this->getResource()
        ));
    }

    /**
     * @param  integer $id
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function showAction($id)
    {
        $object = $this->getRepository()->find($id);

        if (empty($object)) {
            throw $this->createNotFoundException();
        }

        $resource = $this->getResource();

        return $this->render($this->getResource()->getTemplate('show'), array(
            'object' => $object,
            'resource' => $resource
        ));
    }

    /**
     * @param  integer $id
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id, Request $request)
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

        if ($request->getMethod() === 'POST' && $this->getFormHandler()->update($form, $request, $resource->getOption('handler', array()))) {
            $this->get('session')->getFlashBag()->set('success', $this->get('translator')->trans('nekland_admin.success_sentence'));

            /** @var \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(Events::afterUpdate, new AfterUpdateEvent($object));

            $showRoute = $resource->getRoute('show');
            return $this->redirect($this->generateUrl(
                $showRoute['name'],
                array('id' => $object->getId(), 'resource' => $resource->getSlug())
            ));
        }

        return $this->render($this->getResource()->getTemplate('edit'), array(
            'object' => $object,
            'form'   => $form->createView(),
            'resource' => $resource
        ));
    }

    /**
     * @param  Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $resource    = $this->getResource();
        $objectClass = $resource->getModel();
        $route       = $resource->getRoute('create');
        $object      = new $objectClass();
        $form        = $this->getForm(
            $object,
            $this->generateUrl(
                $route['name'],
                array('resource' => $resource->getSlug())
            )
        );

        if ($request->getMethod() === 'POST' && $this->getFormHandler()->create($form, $request)) {
            $this
                ->get('session')
                ->getFlashBag()
                ->set('success', $this->get('translator')->trans('nekland_admin.success_sentence'))
            ;

            /** @var \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(Events::afterCreate, new AfterCreateEvent($object));

            $showRoute = $resource->getRoute('show');
            return $this->redirect($this->generateUrl(
                $showRoute['name'],
                array('id' => $object->getId(), 'resource' => $resource->getSlug())
            ));
        }

        return $this->render($this->getResource()->getTemplate('new'), array(
            'form'   => $form->createView(),
            'resource' => $resource
        ));
    }

    /**
     * @param  integer $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function deleteAction($id)
    {
        $object = $this->getRepository()->find($id);

        if (!$this->canDelete($object)) {
            throw $this->createAccessDeniedException('Deletion is not authorized according to admin configuration.');
        }
        if (empty($object)) {
            throw $this->createNotFoundException('I can\'t found your object, sorry !');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        $em->flush();

        $resource = $this->getResource();

        return $this->redirect($this->generateUrl(
            $resource->getRoute('index'),
            array('resource' => $resource->getSlug())
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
     * @param \Nekland\Bundle\BaseAdminBundle\Crud\Model\Resource $resource
     * @return self
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
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Form\HandlerInterface
     */
    protected function getFormHandler()
    {
        if (empty($this->handler)) {
            $handler = $this->getResource()->getClasses();
            $handler = $handler['handler'];

            if (is_string($handler)) {
                return $this->get($handler);
            }
            return $handler;
        }

        return $this->handler;
    }

    protected function getForm($object, $url)
    {
        $resource = $this->getResource();

        if ($resource->hasFormType()) {

            $type = $resource->getFormType();

            if (is_string($type)) {
                if ($this->container->has($type)) {
                    $type = $this->get($type);
                }
            }

            return $this->getFormHandler()->getForm($type, $object, $url);
        }

        $formGenerator = $this->get('nekland_admin.crud.form.generator');
        return $formGenerator->generate($object, $resource, $url);
    }

    protected function canDelete($object)
    {
        $rights = $this->resource->getRights();

        return $rights['delete'];
    }
}
