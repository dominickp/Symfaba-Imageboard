<?php

namespace Dominick\ImageboardBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\ImageboardBundle\Entity\Thread;
use Dominick\ImageboardBundle\Entity\User;
use Dominick\ImageboardBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class ThreadController extends Controller
{
	public function indexAction()
	{
		return 'Hello World';
	}

	public function newAction(Request $request)
	{
		// = $this->container->get('request');
		$session = $request->getSession();
		$session->start();

		$thread = new Thread();

		$form = $this->createFormBuilder($thread)
			->add('subject', 'text')
			->add('message', 'text')
			->add('image', 'file')

			->add('Submit', 'submit')
			->getForm();

		$form->handleRequest($request);


		if ($form->isValid())
		{

			$thread = $form->getData();

			// Move to this directory once upload is successful
			$dir =  $this->get('kernel')->getRootDir() . '/../web'.'/img_data/thread/';

		//	include($dir.'test.txt');
			// Sanitize and keep the original file name
			//$originalImageName = htmlspecialchars($thread['image']->getData()->getClientOriginalName());
			// Move to the thread image directory

		//	print_r($dir); die;

			$form['image']->getData()->move($dir, 'test.jpg');






			// Get and set the current user
			$currentUser = $this->getUser();
			$thread->setUser($currentUser);

			$em = $this->getDoctrine()->getManager();

			// Save the new thread
			$em->persist($thread);
			$em->flush();

			return $this->redirect($this->generateUrl('imageboard_homepage'));
		}

		return $this->render('DominickImageboardBundle:User:register.html.twig', array(
			'form' => $form->createView(),
		));
	}
}