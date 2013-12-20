<?php

namespace Dominick\ImageboardBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\ImageboardBundle\Entity\Thread;
use Dominick\ImageboardBundle\Image\ResizeImage;
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
			->add('message', 'textarea')
			->add('image', 'file')

			->add('Submit', 'submit')
			->getForm();

		$form->handleRequest($request);


		if ($form->isValid())
		{

			$thread = $form->getData();

			// Move to this directory once upload is successful
			$dir =  $this->get('kernel')->getRootDir() . '/../web'.'/img_data/thread/';

			// Sanitize and keep the original file name
			$originalImageName = time().'_'.htmlspecialchars($form['image']->getData()->getClientOriginalName());
			// Move to the thread image directory
			$form['image']->getData()->move($dir, $originalImageName);

			// Set the file name in the database
			$thread->setImage($originalImageName);

			// Now work on the thumbnail
			$resize = new ResizeImage($dir.$originalImageName);
			$resize->resizeTo(250, 250, 'maxWidth');
			$resize->saveImage($dir.'thumb_'.$originalImageName);

			// Save thumbnail name to the database
			$thread->setThumbnail('thumb_'.$originalImageName);

			// Get and set the current user
			$currentUser = $this->getUser();
			$thread->setUser($currentUser);

			$em = $this->getDoctrine()->getManager();

			// Save the new thread
			$em->persist($thread);
			$em->flush();

			return $this->redirect($this->generateUrl('imageboard_homepage'));
		}

		return $this->render('DominickImageboardBundle:Thread:thread_new.html.twig', array(
			'form' => $form->createView(),
		));
	}

    public function viewThreadAction($id)
    {
        // Display the last 10 threads
        $thread = $this->getDoctrine()
            ->getRepository('DominickImageboardBundle:Thread')
            //    ->findAll();
            ->find(array('id' => $id) // $orderBy
            );

        $replies = $this->getDoctrine()
            ->getRepository('DominickImageboardBundle:Reply')
            ->findBy(
                array('thread' => $id), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );
        return $this->render('DominickImageboardBundle:Thread:thread_view.html.twig', array(
            'replies' => $replies,
            'thread' => $thread,
        ));
    }

	public function deleteAction($id){
		$em = $this->getDoctrine()->getManager();
		$thread = $this->getDoctrine()->getRepository('DominickImageboardBundle:Thread')
			->findOneBy(array(
				'id'=>$id
			));

		$em->remove($thread);
		$em->flush();

		return $this->redirect($this->generateUrl('imageboard_homepage'));

	}

}