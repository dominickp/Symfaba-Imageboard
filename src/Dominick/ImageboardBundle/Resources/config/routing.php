<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('dominick_imageboard_homepage', new Route('/hello/{name}', array(
    '_controller' => 'DominickImageboardBundle:Default:index',
)));

return $collection;
