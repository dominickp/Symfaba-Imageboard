<?php

namespace Dominick\ImageboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickImageboardBundle:Default:home.html.twig', array('name' => 'test'));
    }
}
