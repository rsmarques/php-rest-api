<?php

use app\http\Router as Router;
use app\http\Request as Request;
use app\http\Response as Response;

$router     = new Router;

// Home view
$router->get('/', function () {
    return new Response(file_get_contents(__DIR__ . "/../public/views/home.html"), Response::HTTP_OK);
});

// Contacts REST API
$router->get('/contacts/{id?}/', 'ContactController@get');
$router->post('/contacts/{id?}/', 'ContactController@create');
$router->put('/contacts/{id?}/', 'ContactController@update');
$router->delete('/contacts/{id?}/', 'ContactController@delete');

$router->match(Request::createFromGlobals());
