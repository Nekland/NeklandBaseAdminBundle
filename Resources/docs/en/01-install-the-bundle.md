Installation
============

1) Download
-----------

Use composer to install the bundle:

```JSON
composer require nekland/base-admin-bundle
```

2) Load the bundle via your AppKernel
-------------------------------------

Open your "AppKernel.php" file.

Add the following lines to the file.

*Notice that if you don't already install [KnpMenuBundle](http://github.com/KnpLabs/KnpMenuBundle), you have to register it too.*

```PHP
// AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Nekland\Bundle\BaseAdminBundle\NeklandBaseAdminBundle(),

        // Only if you don't already have this line
        // new Knp\Bundle\MenuBundle\KnpMenuBundle(),

        // Only if you don't already have this line
        // new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(),
    );
}
```

3) Add the bundle to your routing
---------------------------------

As any other bundle who have theses own routes, you have to include it in your routing.

Open your "routing.yml" file and add theses lines (you can of course modify the prefix):

```YAML
nekland_admin:
    resource: "@NeklandBaseAdminBundle/Resources/config/routing.yml"
    prefix:   /admin
```

4) Add the bundle to assetic
----------------------------

Open your "config.yml" file (or any other file where you configure assetic).

Add (or merge) this configuration:

```YAML
assetic:
    bundles:        [ NeklandBaseAdminBundle ]
```

6) The bundle is ready to work
------------------------------


Thank you for using it :-) .
