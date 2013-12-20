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
				array('updated' => 'DESC'), // $orderBy
				10, // $limit
				0 // $offset
			);

        // Display the last 3 replies
        foreach($threads as &$thread)
        {
            $threadId = $thread->getId();
            $previewReplies = $this->getDoctrine()
                ->getRepository('DominickImageboardBundle:Reply')
                ->findBy(
                    array('thread' => $threadId), // $where
                    array('created' => 'DESC'), // $orderBy
                    3, // $limit
                    0 // $offset
                );
            // Flip the results the other way
            $previewReplies = array_reverse($previewReplies);
            $thread->previewReplies = $previewReplies;
        }

        return $this->render('DominickImageboardBundle:Default:home.html.twig', array('threads' => $threads, 'nextPage' => 1));
    }

	public function nextAction($pageNumber)
	{

		$limit = 10;
		$offset = $limit*$pageNumber;

		// Display the last 10 threads starting from the page
		$threads = $this->getDoctrine()
			->getRepository('DominickImageboardBundle:Thread')
			//    ->findAll();
			->findBy(
				array(), // $where
				array('updated' => 'DESC'), // $orderBy
				$limit, // $limit
				$offset // $offset
			);

		// Display the last 3 replies
		foreach($threads as &$thread)
		{
			$threadId = $thread->getId();
			$previewReplies = $this->getDoctrine()
				->getRepository('DominickImageboardBundle:Reply')
				->findBy(
					array('thread' => $threadId), // $where
					array('created' => 'DESC'), // $orderBy
					3, // $limit
					0 // $offset
				);
			// Flip the results the other way
			$previewReplies = array_reverse($previewReplies);
			$thread->previewReplies = $previewReplies;
		}

		return $this->render('DominickImageboardBundle:Default:home.html.twig', array('threads' => $threads, 'nextPage' => $pageNumber+1));

	}
}
