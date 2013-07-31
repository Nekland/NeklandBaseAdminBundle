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

1. "prefix": you don't have to define each route here, when you have the following routes, you just need to define the prefix:
   *  admin_news_index
   *  admin_news_new
   *  admin_news_create
   *  admin_news_edit
   *  admin_news_update
   *  admin_news_delete

2. "formType": an instance of your form type
3. "repository": The Symfony path to your repository
4. "class": an instance of your entity

2) The Form
-----------

