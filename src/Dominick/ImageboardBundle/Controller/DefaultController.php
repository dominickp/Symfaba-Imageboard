<?php

namespace Dominick\ImageboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
		// Display the last 10 threads
		$threads = $this->getDoctrine()
			->getRepository('DominickImageboardBundle:Thread')
			//    ->findAll();
			->findBy(
				array(), // $where
				array('created' => 'DESC'), // $orderBy
				10, // $limit
				0 // $offset
			);


        return $this->render('DominickImageboardBundle:Default:home.html.twig', array('threads' => $threads));
    }
}
