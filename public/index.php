<?php

// autoloader
require_once(__DIR__ . '/../app/lib/SplClassLoader.php');
// firebase
require_once(__DIR__ . '/../app/lib/firebase/firebaseLib.php');

$loader             = new SplClassLoader('app', __DIR__ . '/../');
$loader->register();

$allowedMethods     = ['GET', 'POST', 'PUT', 'DELETE'];

$requestMethod      = $_SERVER['REQUEST_METHOD'];

// Assuring httpd method is a RESTful request
if (!in_array($requestMethod, $allowedMethods)) {
    $api            = new app\controllers\ApiController;
    return $api->responseWithErrors("Method not allowed", 402);
}

$requestUri         = $_SERVER['REQUEST_URI'];
$requestParsed      = parse_url($requestUri);

// Parsing request_uri assuring it's a RESTful request
$requestUrl         = $requestParsed['path'];
if (isset($requestParsed['query'])) {
    parse_str($requestParsed['query'], $requestQuery);
} else {
    $requestQuery   = [];
}

$requestUrlParams   = explode('/', trim($requestUrl, '/'));

// Not a REST request, URL has to be /resource/{?id}
if (count($requestUrlParams) > 2) {
    $api            = new app\controllers\ApiController;
    return $api->responseWithErrors("Bad Request", 400);
}

$requestModel       = strtolower($requestUrlParams[0]);
$requestModelId     = isset($requestUrlParams[1]) ? $requestUrlParams[1] : null;

$controllerName     = 'app\controllers\\' . ucfirst($requestModel) . 'Controller';

// Checking if controller is defined
if (!class_exists($controllerName)) {
    $api            = new app\controllers\ApiController;
    return $api->responseWithErrors("Model not defined", 405);
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
