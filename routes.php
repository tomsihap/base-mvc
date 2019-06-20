<?php

// Create Router instance
$router = new Router();

// example.com
$router->get('/', 'PagesController@home' );

// example.com/about
$router->get('/about', 'PagesController@about');

$router->get('/contactez-nous', 'PagesController@contact');
$router->post('/contactez-nous', 'PagesController@traitementForm');


// example.com/articles
$router->get('/articles', 'ArticlesController@index');


// Run it!
$router->run();