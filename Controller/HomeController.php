<?php
/**
 * Created by JetBrains PhpStorm.
 * User: nek
 * Date: 26/07/13
 * Time: 19:00
 * To change this template use File | Settings | File Templates.
 */

namespace Nekland\Bundle\BaseAdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('NeklandBaseAdminBundle:Home:index.html.twig');
    }
}