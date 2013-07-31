Installation
============

1) Download
-----------

Open your "composer.json" file and add this line:
```JSON
{
    "require": {
        "nekland/base-admin-bundle": "dev-master@dev"
    }
}
```

Use composer to install the bundle:
```JSON
composer update nekland/base-admin-bundle
```

2) Load the bundle via your AppKernel
-------------------------------------

Open your "AppKernel.php" file.

Add the following lines to the file.

*Notice that if you doen't already install KnpMenuBundle, you have to register it too.*
```PHP
// AppKernel.php
public function registerBundles()
{
    $bundles = array(
        // ...
        new Nekland\Bundle\BaseAdminBundle\NeklandBaseAdminBundle(),

        // Only if you don't already have this line
        // new Knp\Bundle\MenuBundle\KnpMenuBundle(),
    );

}
```

3) Add the bundle to assetic
----------------------------

Open your "config.yml" file (or any other file where you configure assetic).

Add (or merge) this configuration:
```YAML
assetic:
    bundles:        [ NeklandBaseAdminBundle ]
```

4) Add the form theming to twig
-------------------------------

The bundle uses bootstrap to generate the admin interface. With this theme, symfony will generate correct forms for
bootstrap by itself.

Open your config.yml. And merge this configuration:
```JSON
# Twig Configuration
twig:
    form:
        resources:
            - 'NeklandBaseAdminBundle::form.html.twig'
```

5) The bundle is ready to work
------------------------------