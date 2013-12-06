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

        foreach($threads as &$thread)
        {
            $threadId = $thread->getId();

            // Display the last 3 replies
            $previewReplies = $this->getDoctrine()
                ->getRepository('DominickImageboardBundle:Reply')
                ->findBy(
                    array('thread' => $threadId), // $where
                    array('created' => 'DESC'), // $orderBy
                    3, // $limit
                    0 // $offset
                );
            $previewReplies = array_reverse($previewReplies);
            $thread->previewReplies = $previewReplies;
            //var_dump($replies);
        }


        return $this->render('DominickImageboardBundle:Default:home.html.twig', array('threads' => $threads));
    }
}
