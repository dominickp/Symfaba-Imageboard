<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

// Home
$collection->add('imageboard_homepage', new Route('/', array(
    '_controller' => 'DominickImageboardBundle:Default:index',
)));

// User
$collection->add('imageboard_user_register', new Route('/register/', array(
    '_controller' => 'DominickImageboardBundle:User:register',
)));


return $collection;
