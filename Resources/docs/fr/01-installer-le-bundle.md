Installation
============

1) Téléchargement
-----------------

Ouvrez votre fichier "composer.json" et ajoutez cette ligne:

```JSON
{
    "require": {
        "nekland/base-admin-bundle": "dev-master@dev"
    }
}
```

Utilisez composer pour installer le bundle:

```JSON
composer update nekland/base-admin-bundle
```

2) Chargez le bundle via votre AppKernel
----------------------------------------

Ouvre votre fichier "AppKernel.php".

Ajoutez les lignes suivantes au fichier.

*Remarquez que si vous n'avez pas déjà installé KnpBundle, vous devez le charger aussi.*

```PHP
// AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Nekland\Bundle\BaseAdminBundle\NeklandBaseAdminBundle(),

        // Uniquement si vous n'avez pas déjà cette ligne
        // new Knp\Bundle\MenuBundle\KnpMenuBundle(),

        // Uniquement si vous n'avez pas déjà cette ligne
        // new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),
    );

}
```

3) Ajoutez le bundle à votre routing
------------------------------------

Tout n'importe quel autre bundle qui possède ses propres routes, vous devez l'inclure dans votre routing.

Ouvrez votre fichier "routing.yml" et ajoutez ces lignes (vous pouvez évidemment modifier le prefixe):

```YAML
nekland_admin:
    resource: "@NeklandBaseAdminBundle/Resources/config/routing.yml"
    prefix:   /admin
```

3) Ajoutez le bundle à assetic
------------------------------

Ouvrez votre fichier "config.yml" (ou n'importe quel fichier où vous configurez assetic).

Ajoutez (ou fusionnez) cette configuration:

```YAML
assetic:
    bundles:        [ NeklandBaseAdminBundle ]
```


4) AJoutez le thème de formulaire à twig
----------------------------------------

Le bundle utilise bootstrap pour générer l'interface de l'administration. Avec ce thème, symfony va générer correctement
les formulaires pour bootstrap

Ouvrez votre fichier "config.yml" et fusionnez cette configuration:

```JSON
# Twig Configuration
twig:
    form:
        resources:
            - 'NeklandBaseAdminBundle::form.html.twig'
```

4) Le bundle est prêt à fonctionner
-----------------------------------
