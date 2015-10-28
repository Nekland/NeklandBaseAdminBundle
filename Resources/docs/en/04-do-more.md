Do more
=======

Personalize the menu of the admin
---------------------------------

To allow you to add more features to your admin dashboard, the menu is of course extensible. It's build with KnpMenu.

In order to make it work, you should listen on the event `nekland_admin.configure.menu`, all events are in the class
`Nekland\Bundle\BaseAdminBundle\Event\Events::onConfigureMenu`, just like in this example:

```php
class MenuListener
{
    /**
     * This method will be executed when the menu will be build.
     *
     * @param \Nekland\Bundle\BaseAdminBundle\Event\OnConfigureMenuEvent $event
     */
    public function onConfigureMenu(OnConfigureMenuEvent $event)
    {
        // "article" is the name of your resource, to get the submenu. But you can create a new part on the menu by
        // getting a new one like "My special awesome part".
        $menu = $event->getMenu('article');

        $subMenu = $menu->addChild('delete_articles', [
            'route' => 'delete_all_articles'
        ]);
        $subMenu->setLabel('Delete all menu'); // Remember, it's a KnpMenu item ;)
    }
}
```

And don't forget to register your listener in the container:

```yaml
services:
    app.listener.admin_menu:
        class: AppBundle\Listener\MenuListener
        tags:
            -  { name: kernel.event_listener, event: nekland_admin.configure.menu, method: onConfigureMenu }
```
