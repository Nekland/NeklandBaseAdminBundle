Make your own administration
============================

Since this bundle is a simple tool, it doesn't do so much. You have to do your usually work:
*  Making a controller
*  Writing routing
*  Making your entities
*  Making your form types
*  Making your own (functional) test

1) The controller
-----------------

You don't have to write any action but the most part of configuration to do is in your controller.

Create your controller and extends from "Nekland\Bundle\BaseAdmin\Controller\CrudController".

```PHP
class MyAdminController extends \Nekland\Bundle\BaseAdmin\Controller\CrudController
{

}
```

You have to configure the controller with defining the missing method: "getParams"


```PHP
public function getParams()
{

    /**
     * @return array
     */
    protected function getParams()
    {
        return array(
            'prefix'     => 'admin_news',
            'formType'   => new NewsType(),
            'repository' => 'NeklandSiteBundle:News',
            'class'      => new News()
        );
    }
}
```

1. "prefix": you don't have to define each route when your routes share the same prefix, you just need to define the
   prefix, the generated routes will be:
   *  admin_news_index
   *  admin_news_new
   *  admin_news_create
   *  admin_news_edit
   *  admin_news_update
   *  admin_news_delete

2. "formType": an instance of your form type
3. "repository": The Symfony path to your repository
4. "class": an instance of your entity

For more information, refer to the documentation page "Reference" where all options are documented.
You can also take a look at our controller class witch is comment to help you.

2) The Form
-----------

The form is a classical symfony form. You just have to instantiate your form type in configuration as explained before.

3) The entity
-------------

To work with our crud controller, your entity have to implement the "CrudableInterface". Here is an example of
implementation:

```PHP
<?php

namespace Nekland\Bundle\SiteBundle\Entity;

use Nekland\Bundle\BaseAdminBundle\Crud\CrudableInterface;

class News implements CrudableInterface
{
    /**
     * Return parameters needed for the route in administration
     *
     * @return array
     */
    public function getRouteParams()
    {
        return array(
            'id' => $this->id
        );
    }
}
```

4) The routing
--------------

Your routing should look like this if you defined only the prefix in your controller configuration:

```YAML
admin_news_index:
    pattern: /news/index
    defaults: { _controller: NeklandSiteBundle:Backend/News:index }

admin_news_new:
    pattern: /news/new
    defaults: { _controller: NeklandSiteBundle:Backend/News:new }

admin_news_create:
    pattern: /news/create
    defaults: { _controller: NeklandSiteBundle:Backend/News:create }

admin_news_edit:
    pattern: /news/edit/{id}
    defaults: { _controller: NeklandSiteBundle:Backend/News:edit }
    requirements:
        id: \d+

admin_news_update:
    pattern: /news/update/{id}
    defaults: { _controller: NeklandSiteBundle:Backend/News:update }
    requirements:
        id: \d+

admin_news_delete:
    pattern: /news/delete
    defaults: { _controller: NeklandSiteBundle:Backend/News:delete }
```

*This routing can be personalized, the only important thing is the prefix "admin_news" on each route.*

5) The menu listener
--------------------

The bundle doesn't add anything to the menu alone, you have to define a listener. The menu uses KnpMenuBundle, so you
can of course refer to this documentation.

To make a listener you can define this kind of class:

```PHP
<?php

namespace Nekland\Bundle\SiteBundle\Listener;


use Nekland\Bundle\BaseAdminBundle\Event\OnConfigureMenuEvent;

class AdminMenu
{
    /**
     * This method will be executed when the menu will be build.
     *
     * @param OnConfigureMenuEvent $event
     */
    public function onConfigureMenu(OnConfigureMenuEvent $event)
    {
        $menu = $event->getMenu('News');

        $menu->addChild('Ajouter', array('route' => 'admin_news_new'));
    }
}
```

The only obligation is the type of the parameter (that you don't have to precise since PHP don't care about).

The you can uses the method "getMenu" with theses parameters:
*  "Nothing" if you want to get the root node of the menu
*  "Something" if you want to get a part of the menu

Then you have to define your listener as service. Open your "service.yml" file and add this configuration:

```YAML
parameters:
    nekland_site.listener.admin.menu.class: EA\SiteBundle\Listener\AdminMenu

services:
    nekland_site.listener.admin.menu:
        class: %nekland_site.listener.admin.menu.class%
        tags:
            -  { name: kernel.event_listener, event: nekland_admin.configure.menu, method: onConfigureMenu  }
```

The interessing part is the tag:
*  The "name" is to precise that your defining a symfony event listener
*  The "event" is the name of the event you're listening (every event are available in the class Nekland\Bundle\BaseAdminBundle\Event\Events)
*  The "method" is the name of your method in your class to call (it allows you to change the name of the method)

6) The end
----------

Your installation should be ok ! :-)
