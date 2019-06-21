<?php

// Create Router instance
$router = new Router();

// example.com
$router->get('', 'PagesController@home' );

// example.com/about
$router->get('about', 'PagesController@about');

$router->get('contactez-nous', 'PagesController@contact');
$router->post('contactez-nous', 'PagesController@traitementForm');

$router->get('ajouter-article', 'ArticlesController@add');
$router->post('ajouter-article', 'ArticlesController@save');
$router->get('article', 'ArticlesController@show');

// Run it!
$router->run();