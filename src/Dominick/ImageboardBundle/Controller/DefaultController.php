<?php

namespace Dominick\ImageboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('DominickImageboardBundle:Default:index.html.twig', array('name' => $name));
    }
}
