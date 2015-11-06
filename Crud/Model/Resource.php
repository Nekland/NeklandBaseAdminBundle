<?php

/*
 * This file is part of the NekLandBaseAdminBundle package.
 *
 * (c) Nekland <http://nekland.fr/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nekland\Bundle\BaseAdminBundle\Crud\Model;

/**
 * Class Resource
 *
 * Is hydrated with the configuration checked in the configuration class.
 */
class Resource
{
    /**
     * The resource name
     *
     * @var string
     */
    private $name;

    /**
     * The name of the resource as slug
     *
     * @var string
     */
    private $slug;

    /**
     * The plural name of the resource
     *
     * @var string
     */
    private $pluralName;

    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $driver;

    /**
     * @var string
     */
    private $manager;

    /**
     * @var array
     */
    private $classes;

    /**
     * @var array
     */
    private $templates;

    /**
     * @var array
     */
    private $labels;

    /**
     * @var array
     */
    private $actions;

    /**
     * @var Property[]
     */
    private $properties;

    /**
     * @var string
     */
    private $labelTranslation;

    /**
     * @var boolean[]
     */
    private $rights;

    /**
     * @var mixed[]
     */
    private $options;

    public function _construct()
    {
        $this->options = array();
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param array $classes
     * @return self
     */
    public function setClasses(array $classes)
    {
        $this->classes = $classes;

        // make a new instance of the type
        if (is_string($classes['type']) && class_exists($classes['type'])) {
            $type = $classes['type'];
            $this->classes['type'] = new $type();
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param $name
     * @param string|object $class
     * @return self
     */
    public function setClass($name, $class)
    {
        if (is_string($class) && class_exists($class)) {
            $this->classes[$name] = new $class();
        } else {
            $this->classes[$name] = $class;
        }

        return $this;
    }

    /**
     * @param string $driver
     * @return self
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * @return string
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param string $manager
     * @return self
     */
    public function setManager($manager)
    {
        $this->manager = $manager;

        return $this;
    }

    /**
     * @return string
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setPluralName($name)
    {
        $this->pluralName = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return empty($this->pluralName) ? $this->name . 's' : $this->pluralName;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array $routes
     * @return self
     */
    public function setRoutes(array $routes)
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param $name
     * @return string
     */
    public function getRoute($name)
    {
        return $this->routes[$name];
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return $this->classes['model'];
    }

    /**
     * @param array $templates
     * @return self
     */
    public function setTemplates(array $templates)
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * @param $templateName
     * @return string
     */
    public function getTemplate($templateName)
    {
        return $this->templates[$templateName];
    }

    /**
     * @param array $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param array $labels
     */
    public function setLabels(array $labels)
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @return array
     */
    public function getLabels()
    {
        return $this->labels;
    }

    /**
     * @return string|\Symfony\Component\Form\AbstractType
     */
    public function getFormType()
    {
        return $this->classes['type'];
    }

    /**
     * @return bool
     */
    public function hasFormType()
    {
        return !empty($this->classes['type']);
    }

    /**
     * @return string|\Nekland\Bundle\BaseAdminBundle\Crud\Form\Handler
     */
    public function getFormHandler()
    {
        return $this->classes['handler'];
    }

    /**
     * @param \Nekland\Bundle\BaseAdminBundle\Crud\Model\Property[] $properties
     * @return self
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * @return \Nekland\Bundle\BaseAdminBundle\Crud\Model\Property[]
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param $name
     * @return self
     */
    public function getProperty($name)
    {
        return $this->properties[$name];
    }

    /**
     * @param string $name
     * @param Property $property
     * @return self
     */
    public function addProperty($name, Property $property)
    {
        $this->properties[$name] = $property;

        return $this;
    }

    /**
     * @param \mixed[] $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @return \mixed[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getOption($name, $defaultValue = null)
    {
        return !empty($this->options[$name]) ? $this->options[$name] : $defaultValue;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasOption($name)
    {
        return !empty($this->options[$name]);
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    /**
     * @param  string $labelTranslation
     * @return self
     */
    public function setLabelTranslation($labelTranslation)
    {
        $this->labelTranslation = $labelTranslation;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabelTranslation()
    {
        return $this->labelTranslation;
    }

    /**
     * @return boolean[]
     */
    public function getRights()
    {
        return $this->rights;
    }

    /**
     * @param boolean[] $rights
     * @return Resource
     */
    public function setRights($rights)
    {
        $this->rights = $rights;

        return $this;
    }

    /**
     * @param  string $routeName
     * @param  Object $model
     * @return array
     */
    public function getRouteParameters($routeName, $model = null)
    {
        $parameters = array();

        $parameters['resource'] = $this->getSlug();

        if (empty($this->routes[$routeName]) || empty($model)) {
            return $parameters;
        }

        foreach ($this->routes[$routeName]['parameters'] as $parameter) {
            $method = 'get' . ucfirst($parameter);
            $parameters[$parameter] = $model->{$method}();
        }


        return $parameters;
    }

    /**
     * @param string $action_name
     * @param object $model
     * @return array
     */
    public function getActionRouteParameters($action_name, $model=null)
    {
        $parameters = array();

        if (empty($this->actions[$action_name]) || empty($model)) {
            return $parameters;
        }

        foreach ($this->actions[$action_name]['route']['parameters'] as $parameter) {
            if ($parameter === 'resource') {
                $parameters['resource'] = $this->getSlug();
                continue;
            }
            $method = 'get' . ucfirst($parameter);
            $parameters[$parameter] = $model->{$method}();
        }

        return $parameters;
    }
}
