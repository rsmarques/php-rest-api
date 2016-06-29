<?php

use app\http\Router as Router;
use app\http\Response as Response;

$route = new Router;

// Home view
$route->get('/', function () {
    return new Response(file_get_contents(__DIR__ . "/../public/views/home.html"), Response::HTTP_OK);
});

// Contacts API
$route->get('/contact/{id?}/', 'ContactController@get');
$route->post('/contact/{id?}/', 'ContactController@create');
$route->put('/contact/{id?}/', 'ContactController@update');
$route->delete('/contact/{id?}/', 'ContactController@delete');

$route->match($_SERVER, $_POST);
