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
$collection->add('account_login', new Route('/login', array(
    '_controller' => 'DominickImageboardBundle:User:login',
)));
$collection->add('account_login_check', new Route('/login_check'));
$collection->add('account_logout', new Route('/logout'));

// Thread
$collection->add('imageboard_thread_new', new Route('/new', array(
	'_controller' => 'DominickImageboardBundle:Thread:new',
)));

// Reply
$collection->add('imageboard_reply_new', new Route('/reply/{id}', array(
	'_controller' => 'DominickImageboardBundle:Reply:new',
)));

return $collection;
