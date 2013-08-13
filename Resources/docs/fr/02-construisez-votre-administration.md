Construisez votre administration
================================

Ce bundle est un outil simple, il ne fait donc pas "trop" de choses. Vous allez devoir faire votre travail habituel:

*  Construire un contrôleur
*  Écrire les routes
*  Construire vos entitées
*  Construire vos classes de formulaires
*  Écrire vos propres tests (fonctionnels)

1) Le contrôleur
-----------------

Vous n'avez à écrire aucune action mais la majeure partie de la configuration se fait dans votre contrôleur.

Créez votre contrôleur en étendant la classe "Nekland\Bundle\BaseAdmin\Controller\CrudController".

```PHP
class MyAdminController extends \Nekland\Bundle\BaseAdmin\Controller\CrudController
{

}
```

Vous devez configurer le contrôleur en definissant la méthode manquante: "getParams"

```PHP

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
```

1. "prefix": vous n'avez pas à définir chaque route lorsque vos routes possèdent le même prefix,
   il vous suffit de définir l'option "prefix", les routes générées seront:
   *  admin_news_index
   *  admin_news_new
   *  admin_news_create
   *  admin_news_edit
   *  admin_news_update
   *  admin_news_delete

2. "formType": une instance de votre classe de formulaire
3. "repository": Le chemin (version symfony) vers votre repository
4. "class": Une instance de votre entité

Pour plus d'informations, veuillez vous référer à la page de documentation "Référence" où toutes les options sont
documentées. Vous pouvez aussi regarder notre contrôleur qui est bien commenté pour vous aider à comprendre toutes les
options.


2) L'entité
-----------

Pour travailler avec notre contrôleur "crud", votre entité doit implémenter l'interface "CrudableInterface". Voici
un exemple d'implémentation:

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

3) Le formulaire
----------------

Le formulaire est un formulaire symfony classique. Vous devez juste l'instancier dans la configuration comme expliqué
précédemment.

Vous pouvez utiliser la commande symfony suivante:

```BASH
php app/console generate:doctrine:form NeklandSiteBundle:News
```

4) Le routing
-------------

Votre routing devrait ressembler à cela si vous avez simplement défini un prefix dans la configuration de
votre contrôleur:

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

*Ce routing peut être personnalisé, la seule chose importante est le préfixe "admin_news" sur chaque route.*

5) L'écouteur d'évènement pour le menu
--------------------------------------

Le bundle n'ajoute rien au menu de l'administration qu'il génère tout seul, vous devez définir un listener. Le menu
utilise KnpMenuBundle, vous pouvez donc référer à la documentation de ce bundle.

Pour construire un listener vous pouvez définir ce type de classe:

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

La seule obligation est le type du paramètre (que vous n'êtes pas obligé de préciser puisque php s'en fiche).

Vous pouvez utiliser la méthode "getMenu" avec ces paramètres:

*  "Rien" si vous voulez récupérer le nœeud principal du menu
*  "Quelque chose" si vous voulez récupérer une partie du menu

Vous devez ensuite définir votre listener en tant que service. Ouvrez votre fichier "service.yml" et ajoutez cette
configuration:

```YAML
parameters:
    nekland_site.listener.admin.menu.class: Nekland\SiteBundle\Listener\AdminMenu

services:
    nekland_site.listener.admin.menu:
        class: %nekland_site.listener.admin.menu.class%
        tags:
            -  { name: kernel.event_listener, event: nekland_admin.configure.menu, method: onConfigureMenu  }
```

La partie intéressante est le tag:

*  Le "name" sert à préciser que vous définissez un écouteur d'évènement symfony
*  Le "event" est le nom de l'évènement que vous écoutez (tous les évènements sont disponibles dans la classe Nekland\Bundle\BaseAdminBundle\Event\Events)
*  La "method" est le nom de la méthode dans votre classe qui sera appelée (cela vous permet de changer le nom de votre méthode)


6) Fin
------

Votre installation devrait fonctionner ! :-)
