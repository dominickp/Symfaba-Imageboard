<?php

namespace Dominick\ImageboardBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\ImageboardBundle\Entity\Thread;
use Dominick\ImageboardBundle\Entity\Reply;
use Dominick\ImageboardBundle\Entity\User;
use Dominick\ImageboardBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class ReplyController extends Controller
{
	public function indexAction()
	{
		return 'Hello World';
	}

	public function newAction(Request $request, $id)
	{
		// = $this->container->get('request');
		$session = $request->getSession();
		$session->start();

		$reply = new Reply();

		$form = $this->createFormBuilder($reply)
			->add('message', 'textarea')
			->add('image', 'file', array('required' => false))

			->add('Reply', 'submit')
			->getForm();

		$form->handleRequest($request);


		if ($form->isValid())
		{

			$reply = $form->getData();


			// Do these operations if image was submitted
			if(is_object($form['image']->getData())){
				// Move to this directory once upload is successful
				$dir =  $this->get('kernel')->getRootDir() . '/../web'.'/img_data/reply/';
				// Sanitize and keep the original file name
				$originalImageName = time().'_'.htmlspecialchars($form['image']->getData()->getClientOriginalName());
				// Move to the thread image directory
				$form['image']->getData()->move($dir, $originalImageName);
				// Set MD5 of the image
				$reply->setMd5(md5_file($dir.$originalImageName));
				// Set filesize of the image
				$reply->setSize(filesize($dir.$originalImageName));
				// Set the file name in the database
				$reply->setImage($originalImageName);

				// Now work on the thumbnail
				// Crop dimensions.
				$width = 180;
				$height = 180;
				// Set the path to the image to resize
				$input_image = @imagecreatefromjpeg($dir.$originalImageName);

				// Get the size of the original image into an array
				$size = getimagesize($dir.$originalImageName);

				$thumbnail= imagecreatetruecolor($width,$height);
				imagecopyresized( $thumbnail, $input_image, 0,0, 0, 0, $size[0], $size[1], $width, $height );

				$jpgThumbnail = imagegd( $thumbnail, $originalImageName."_thumb.jpg" );
				rename($originalImageName."_thumb.jpg", $dir.$originalImageName."_thumb.jpg");
				// Move to thumbnail directory


					imagedestroy( $thumbnail);

			}

			// Get and set the current thread
			$thread = $this->getDoctrine()
				->getRepository('DominickImageboardBundle:Thread')
				//    ->findAll();
				->findOneBy(
					array('id' => $id) // $where
				);
			$reply->setThread($thread);

			// Get and set the current user
			$currentUser = $this->getUser();
			$reply->setUser($currentUser);

            // Set thread as updated
            $reply->getThread()->setUpdated(new \DateTime("now"));

			$em = $this->getDoctrine()->getManager();

			// Save the new thread
			$em->persist($reply);
			$em->flush();

			return $this->redirect($this->generateUrl('imageboard_thread_view', array('id' => $id)));
		}

		return $this->render('DominickImageboardBundle:Reply:reply_new.html.twig', array(
			'form' => $form->createView(),
            'id' => $id,
		));
	}
}