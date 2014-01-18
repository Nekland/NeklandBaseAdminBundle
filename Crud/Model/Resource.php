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
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
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
    private $display;

    /**
     *
     * @var array
     */
    private $actions;

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
     * @return $this
     */
    public function setClasses(array $classes)
    {
        $this->classes = $classes;

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
     * @param string $driver
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
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
        return empty($this->pluralName) ? $this->name : $this->pluralName;
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
     * @return $this
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
     * @return string
     */
    public function getModel()
    {
        return $this->classes['model'];
    }

    /**
     * @param array $templates
     * @return $this
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
     * @param array $display
     * @return $this
     */
    public function setDisplay(array $display)
    {
        $this->display = $display;

        return $this;
    }

    /**
     * @return array
     */
    public function getDisplay()
    {
        return $this->display;
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
     * @param Object $model
     * @return array
     */
    public function getRouteParameters($routeName, $model=null)
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
     * @param $model
     * @param $action_name
     * @return array
     */
    public function getActionRouteParameters($action_name, $model=null)
    {
        $parameters = array();

        $parameters['resource'] = $this->getSlug();

        if (empty($this->actions[$action_name]) || empty($model)) {
            return $parameters;
        }

        foreach ($this->actions[$action_name]['route']['parameters'] as $parameter) {
            $method = 'get' . ucfirst($parameter);
            $parameters[$parameter] = $model->{$method}();
        }

        return $parameters;
    }
}
