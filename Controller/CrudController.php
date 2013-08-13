<?php

namespace Nekland\Bundle\BaseAdminBundle\Controller;


use Nekland\Bundle\BaseAdminBundle\Crud\Entity\LockableInterface;
use Nekland\Bundle\BaseAdminBundle\Event\AfterCreateEvent;
use Nekland\Bundle\BaseAdminBundle\Event\Events;
use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\Request,
    Nekland\Bundle\BaseAdminBundle\Utils\Utils,
    Nekland\Bundle\BaseAdminBundle\Crud\Form\Handler;


/**
 * Basic controller which should be extends
 * to add/remove/configure features
 */
abstract class CrudController extends Controller
{
    /**
     * Paramaters available for personalize your backend
     * Templates management parameters (all optional):
     *  - index
     *  - new
     *  - edit
     *  - form
     *
     * Routes (needed if you don't precise prefix)
     *  - index
     *  - new
     *  - create
     *  - edit
     *  - update
     *  - delete
     *  - prefix
     *
     * Form parameters (needed):
     *  - form_type (instance of the type you want to use)
     *
     * Entities parameters (needed):
     *  - class
     *  - repository
     *
     * @var array
     */
    protected static $params = array(
        'templates'  => array(
            'index' => 'NeklandBaseAdminBundle:Crud:index.html.twig',
            'new'   => 'NeklandBaseAdminBundle:Crud:new.html.twig',
            'edit'  => 'NeklandBaseAdminBundle:Crud:edit.html.twig',
            'form'  => 'NeklandBaseAdminBundle:Crud:form.html.twig',
        ),
        'routes'     => array(
            'index'  => null,
            'new'    => null,
            'create' => null,
            'edit'   => null,
            'update' => null,
        ),
        'formType'   => null,
        'class'      => null,
        'repository' => null,
        'singular'   => '',
        'plural'     => '',
        'feminine'   => false,
        'createSentence'        => null,
        'updateSentence'        => null,
        'deleteSentence'        => null,
        'lockedSentence'        => 'L\'objet est verrouillé, impossible de le modifier',
        'nonDeletableSentence'  => 'L\'objet ne peut pas être supprimé',
        'orderBy'               => null,
        'sortable'              => false,
        'display'               => array('id' => array('label' => 'N°')),
        'object_actions'        => array()
    );

    /**
     * Will contains an array of parameters from a merge
     * of default params & user params
     *
     * @var array
     */
    protected $mergedParams = null;

    /**
     * Show a list of objects
     *
     * @return Response
     */
    public function indexAction()
    {
        $request   = $this->container->get('request');   // Getting the Request
        $key       = $request->query->get('key');        // Getting the sort Key
        $direction = $request->query->get('direction');  // Getting the sort direction
        $objects   = null;                               // Initializing objects

        // Check input variables
        if ($key != null && $direction != null && in_array($direction, array('asc', 'desc'))) {
            $objects = $this->getRepository()
                ->findBy(
                    array(),
                    array($key => strtoupper($direction))
                )
            ;
        } else {
            $objects = $this->getRepository()
                ->findAll()
            ;
        }

        return $this->render($this->getParam('index', 'templates'), array(
            'objects'       => $objects,
            'currentSort'   => $key,
            'params'        => $this->getMergedParams()
        ));
    }

    /**
     * New action, displays create form
     *
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function newAction()
    {
        return $this->render($this->getParam('new', 'templates'), array(
            'form'   => $this->getFormHandler()->getForm(
                $this->getFormType(),
                $this->createObject(),
                $this->generateUrl($this->getParam('create', 'routes'))
            )->createView()
        ));
    }



    /**
     * Edit action, displays update form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response
     */
    public function editAction(Request $request)
    {
        $entity = $this->findEntity($request);

        return $this->render($this->getParam('edit', 'templates'), array(
            'form'   => $this->getFormHandler()->getForm(
                $this->getFormType(),
                $entity,
                $this->generateUrl($this->getParam('update', 'routes'), array('id' => $entity->getId()))
            )->createView(),
            'object' => $entity
        ));
    }

    /**
     * Create action, computes create form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $handler = $this->getFormHandler();
        $object  = $this->createObject();
        $form = $handler->getForm(
            $this->getFormType(),
            $object,
            $this->generateUrl($this->getParam('create', 'routes'))
        );

        if ($handler->create($form, $request)) {
            $this->get('session')->getFlashBag()->set('success', $this->getParam('createSentence'));

            /** @var \Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher $dispatcher */
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(Events::afterCreate, new AfterCreateEvent($object));

            return $this->redirectIndex();
        }
    }

    /**
     * Update action, computes update form
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Bundle\FrameworkBundle\Controller\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $entity  = $this->findEntity($request);
        $handler = $this->getFormHandler();
        $form = $handler->getForm(
            $this->getFormType(),
            $entity,
            $this->generateUrl($this->getParam('update', 'routes'), array('id' => $entity->getId()))
        );

        if ($entity instanceof LockableInterface && $entity->isLocked()) {
            $this->get('session')->getFlashBag()->set('error', $this->getParam('lockedSentence'));

            return $this->redirectIndex();
        }

        if ($handler->update($form, $request)) {
            $this->get('session')->getFlashBag()->set('success', $this->getParam('updateSentence'));

            return $this->redirectIndex();
        }

        return $this->render($this->getParam('edit', 'templates'), array(
            'form'      => $form->createView(),
            'object'    => $entity
        ));
    }

    /**
     * Delete action
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $entity = $this->findEntity($request);
        $em = $this->getDoctrine()->getManager();


        if ($entity instanceof LockableInterface && $entity->isDeletable()) {
            $this->get('session')->getFlashBag()->set('error', $this->getParam('nonDeletableSentence'));

            return $this->redirectIndex();
        }

        $em->remove($entity);
        $em->flush();

        $this->get('session')->getFlashBag()->set('success', $this->getParam('deleteSentence'));
        return $this->redirectIndex();
    }

    /**
     * Overrides the base controller render, and add the params to view
     *
     * @{inheritDoc}
     */
    public function render($view, array $parameters = array(), Response $response = null)
    {
        $parameters['params'] = $this->getMergedParams();

        return parent::render($view, $parameters, $response);
    }

    /**
     * Return the formtype
     *
     * @return \Symfony\Component\Form\FormTypeInterface
     */
    protected function getFormType()
    {
        return $this->getParam('formType');
    }

    /**
     * Return an Entity
     *
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Entity\CrudableInterface
     */
    protected function createObject()
    {
        $res = $this->getParam('class');

        if (!($res instanceof \Nekland\Bundle\BaseAdminBundle\Crud\Entity\CrudableInterface))
            throw new \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException("The entity have to be an instance of CrudableInterface");

        return $res;
    }

    /**
     * Get the repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->getDoctrine()->getRepository($this->getParam('repository'));
    }

    /**
     * Find an Entity
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Entity\CrudableInterface
     */
    protected function findEntity(Request $request)
    {
        $id = $request->attributes->get('id');
        if (is_null($id)) {
            $id = $request->query->get('id');
        }

        $entity = $this->getRepository()->find($id);
        if(!$entity) {
            throw $this->createNotFoundException(sprintf(
                'The entity "%s" searched by the BaseAdminBundle with id "%s" was not found.',
                $this->getParam('class'),
                $request->attributes->get('id')
            ));
        }
        return $entity;
    }

    /**
     * Return a parameter
     *
     * @param $name
     * @return mixed
     */
    protected function getParam($name, $category = null)
    {
        $this->getMergedParams();

        if (empty($category)) {
            return $this->mergedParams[$name];
        } else {
            return $this->mergedParams[$category][$name];
        }

    }

    /**
     * Merge parameters with user params
     * add some traitment on those which need
     *
     * @return array
     */
    protected function getMergedParams()
    {
        if (null !== $this->mergedParams) {
            return $this->mergedParams;
        }

        $params = Utils::array_merge_recursive(self::$params, $this->getParams());



        // Generate routes if the prefix is specified
        if (isset($params['prefix'])) {
            foreach (array('index', 'new', 'create', 'edit', 'update', 'delete') as $action) {
                $params['routes'][$action] = $params['prefix'].'_'.$action;
            }
        }

        // Getting the name of the object
        if (empty($params['singular'])) {
            $params['singular'] = strtolower(Utils::getRealClass($params['class']));
        }
        if (empty($params['plural'])) {
            $params['plural'] = strtolower(Utils::getRealClass($params['class'])) . 's';
        }

        // Spelling articles
        $startVoyel = in_array(strtolower($params['singular'][0]), array('a', 'e', 'i', 'o', 'u', 'y'));
        $params['articles'] = array(
            'singular' => array(
                'undefined' => $params['feminine'] ? 'une' : 'un',
                'defined'   => $startVoyel ? 'L\'' : ($params['feminine'] ? 'La' : 'Le'),
            ),
            'plural' => array('undefined' => 'Des', 'defined' => 'Les')
        );

        foreach (array(
            'createSentence' => '%s %s a été créé%s avec succès.',
            'updateSentence' => '%s %s a été modifié%s avec succès.',
            'deleteSentence' => '%s %s a été supprimé%s avec succès.'
        ) as $key => $sentence) {
            $params[$key] = $params[$key] ?: sprintf(
                $sentence,
                $params['articles']['singular']['defined'],
                $params['singular'],
                $params['feminine'] ? 'e' : ''
            );
        }




        return $this->mergedParams = $params;

    }

    /**
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Form\Handler
     */
    protected function getFormHandler()
    {
        return new Handler($this->getDoctrine()->getManager(), $this->get('form.factory'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectIndex()
    {
        return $this->redirect($this->generateUrl($this->getParam('index', 'routes')));
    }

    /**
     * @abstract
     * @return array
     */
    abstract protected function getParams();
}
