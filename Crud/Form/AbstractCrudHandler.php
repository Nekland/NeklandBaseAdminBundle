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


use Nekland\Bundle\BaseAdminBundle\Crud\Exception\MissingOptionException;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractCrudHandler implements HandlerInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /**
     * @var Generator
     */
    protected $generator;

    /**
     * This array will be needed most part of the time
     * @var array
     */
    private $options;

    abstract protected function executeCreate(Form $form, array $options);
    abstract protected function executeUpdate(Form $form, array $options);

    public function __construct(FormFactory $formFactory, Generator $generator)
    {
        $this->formFactory = $formFactory;
        $this->generator   = $generator;
    }

    /**
     * @param  FormTypeInterface                          $type
     * @param  object                                     $object
     * @param  string                                     $action
     * @return Form|\Symfony\Component\Form\FormInterface
     */
    public function getForm(FormTypeInterface $type, $object, $action)
    {
        return $this->formFactory->create($type, $object, array(
            'method' => 'POST',
            'action' => $action
        ));
    }

    /**
     * @param Form    $form
     * @param Request $request
     * @return bool
     */
    public function create(Form $form, Request $request, array $options=array())
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->executeCreate($form, $options);

            return true;
        }

        return false;
    }

    /**
     * @param Form    $form
     * @param Request $request
     * @param array   $options
     * @return bool
     */
    public function update(Form $form, Request $request, $options = array())
    {
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->executeUpdate($form, $options);

            return true;
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     * @throws \Nekland\Bundle\BaseAdminBundle\Crud\Exception\MissingOptionException
     */
    protected function getOptions()
    {
        if ($this->options === null) {
            throw new MissingOptionException('You forgot to set options for the handler');
        }

        return $this->options;
    }

    /**
     * @param $name
     * @return mixed
     * @throws \Nekland\Bundle\BaseAdminBundle\Crud\Exception\MissingOptionException
     */
    protected function getOption($name)
    {
        if ($this->options === null) {
            throw new MissingOptionException('You forgot to set options for the handler');
        }

        return $this->options[$name];
    }
} 