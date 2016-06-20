<?php

// autoloader
require_once(__DIR__ . '/../app/lib/SplClassLoader.php');

$loader             = new SplClassLoader('app', __DIR__ . '/../');
$loader->register();
$firebaseLoader     = new SplClassLoader('Firebase', __DIR__ . '/../app/lib');
$firebaseLoader->register();

// Instantiating ApiController
$api                = new app\controllers\ApiController;

$allowedMethods     = ['GET', 'POST', 'PUT', 'DELETE'];
$requestMethod      = $_SERVER['REQUEST_METHOD'];

// Invalid URL error messages
$invalidUrlMessage  = 'Invalid URL! Usage: /{modelName}/{id?}; Methods: GET, POST, PUT, DELETE';

// Assuring httpd method is a RESTful request
if (!in_array($requestMethod, $allowedMethods)) {
    return $api->responseWithErrors($invalidUrlMessage, 402);
}

$requestUri         = $_SERVER['REQUEST_URI'];
$requestParsed      = parse_url($requestUri);

if (empty($requestParsed['path'])) {
    return $api->responseWithErrors($invalidUrlMessage, 402);
}

// Parsing request_uri assuring it's a RESTful request
$requestUrl         = $requestParsed['path'];
$requestUrlParams   = explode('/', trim($requestUrl, '/'));

if (empty($requestUrlParams[0])) {
    // Root URL, rendering home view
    return $api->responseWithView(file_get_contents(__DIR__ . "/views/home.html"), 200);
}

if (count($requestUrlParams) > 2) {
    // Not a REST request, URL has to be /resource/{id?}
    return $api->responseWithErrors($invalidUrlMessage, 400);
}


$requestModel       = strtolower($requestUrlParams[0]);
$requestModelId     = isset($requestUrlParams[1]) ? $requestUrlParams[1] : null;

$controllerName     = 'app\controllers\\' . ucfirst($requestModel) . 'Controller';

// Checking if controller is defined
if (!class_exists($controllerName)) {
    return $api->responseWithErrors("Model [$requestModel] not defined", 405);
}

$controller         = new $controllerName;

switch ($requestMethod) {
    case 'GET':
        return $controller->get($requestModelId);
        break;
    case 'POST':
        return $controller->create($requestModelId);
        break;
    case 'PUT':
        return $controller->update($requestModelId);
        break;
    case 'DELETE':
        return $controller->delete($requestModelId);
        break;

    default:
        break;
}
