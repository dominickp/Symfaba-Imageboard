<?php

namespace Dominick\ImageboardBundle\Controller;

// Stuff for DB insert
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dominick\ImageboardBundle\Entity\User;
use Dominick\ImageboardBundle\Entity\Apartment;
use Dominick\ImageboardBundle\Entity\Role;
use Symfony\Component\HttpFoundation\Response;

// Login/Security
use Symfony\Component\Security\Core\SecurityContext;

// Forms
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function indexAction()
    {
        return $this->render('DominickImageboardBundle:Default:index.html.twig', array());
    }

    public function registerAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();
        $session->start();

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', 'email')
            ->add('displayname', 'text')
            ->add('password', 'repeated', array(
                'first_name' => 'password',
                'second_name' => 'confirm',
                'type' => 'password',
            ))
            ->add('Register', 'submit')
            ->getForm();

        $form->handleRequest($request);


        if ($form->isValid())
        {

            $user = $form->getData();

            // Grab the security settings for this class from security.yml
            $factory = $this->get('security.encoder_factory');
            // Get the encoder
            $encoder = $factory->getEncoder($user);

            // Encrypt, then set the password. Salt is generated in the User entity.
            $user->setPassword(
                $encoder->encodePassword($user->getPassword(), $user->getSalt())
            );

            // Set the default role for this type of registration
            $default_role = $this->getDoctrine()->getRepository('DominickImageboardBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
            // Call the addRole method with the role I've selected
            $user->addRole($default_role);

            $em = $this->getDoctrine()->getManager();
            // Save the new row you created when you initialized User
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('imageboard_homepage'));
        }

        return $this->render('DominickImageboardBundle:User:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function loginAction(Request $request)
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('DominickImageboardBundle:User:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
        ));
    }
    public function editAccountAction(Request $request)
    {
        $request = $this->getRequest();
        $user = $this->getUser();
        $apartment = $user->getApartment();
        $currentApartmentId = $apartment->getId();

        // Get roommates
        $roommates = $this->getDoctrine()
            ->getRepository('DominickImageboardBundle:User')
            //    ->findAll();
            ->findBy(
                array('apartmentId' => $currentApartmentId), // $where
                array('created' => 'ASC'), // $orderBy
                999, // $limit
                0 // $offset
            );

        // This is how you auto fill form data
        // $task->setTask('Write a blog post');

        $form = $this->createFormBuilder($user)
            ->add('username', 'email')
            ->add('fullname', 'text')
            ->add('password', 'repeated', array(
                'first_name' => 'password',
                'second_name' => 'confirm',
                'type' => 'password',
            ))
            ->add('Update', 'submit')
            ->getForm();


        $form->handleRequest($request);

        // Default false
        $updateSuccess = false;

        // If the form is valid, we should be submitting the data, right?
        if ($form->isValid())
        {
            $user = $form->getData();

            // Grab the security settings for this class from security.yml
            $factory = $this->get('security.encoder_factory');
            // Get the encoder
            $encoder = $factory->getEncoder($user);

            // Encrypt, then set the password. Salt is generated in the User entity.
            $user->setPassword(
                $encoder->encodePassword($user->getPassword(), $user->getSalt())
            );

            // Set the default role for this type of registration
            //$default_role = $this->getDoctrine()->getRepository('DominickRoommateBundle:Role')->findOneBy(array('role' => 'ROLE_USER'));
            // Call the addRole method with the role I've selected
            //$user->addRole($default_role);

            // Get the entity manager
            $em = $this->getDoctrine()->getManager();
            // Save the new row you created when you initialized User
            $em->persist($user);
            // Fire!
            $em->flush();

            $updateSuccess = true;
        }
        return $this->render('DominickImageboardBundle:User:editaccount.html.twig', array(
            'form' => $form->createView(),
            'success' => $updateSuccess,
            'apartment' => $apartment,
            'roommates' => $roommates,
        ));
    }
}