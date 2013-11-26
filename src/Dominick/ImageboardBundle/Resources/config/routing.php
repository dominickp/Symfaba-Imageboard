<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('dominick_imageboard_homepage', new Route('/hello/{name}', array(
    '_controller' => 'DominickImageboardBundle:Default:index',
)));

$collection->add('dominick_imageboard_homepage', new Route('/test/', array(
    '_controller' => 'DominickImageboardBundle:Default:index',
)));

return $collection;
