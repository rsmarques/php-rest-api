<?php

// autoloader
require __DIR__ . '/../vendor/autoload.php';

use app\http\Response as Response;

$allowedMethods     = ['GET', 'POST', 'PUT', 'DELETE'];
$requestMethod      = $_SERVER['REQUEST_METHOD'];

// Invalid URL error messages
$invalidUrlMessage  = 'Invalid URL! Usage: /{modelName}/{id?}; Methods: GET, POST, PUT, DELETE';

// Assuring httpd method is a RESTful request
if (!in_array($requestMethod, $allowedMethods)) {
    return Response::error($invalidUrlMessage, Response::HTTP_METHOD_NOT_ALLOWED);
}

$requestUri         = $_SERVER['REQUEST_URI'];
$requestParsed      = parse_url($requestUri);

if (empty($requestParsed['path'])) {
    return Response::error($invalidUrlMessage, Response::HTTP_NOT_FOUND);
}

// Parsing request_uri assuring it's a RESTful request
$requestUrl         = $requestParsed['path'];
$requestUrlParams   = explode('/', trim($requestUrl, '/'));

if (empty($requestUrlParams[0])) {
    // Root URL, rendering home view
    return new Response(file_get_contents(__DIR__ . "/views/home.html"), Response::HTTP_OK);
}

if (count($requestUrlParams) > 2) {
    // Not a REST request, URL has to be /resource/{id?}
    return Response::error($invalidUrlMessage, Response::HTTP_NOT_FOUND);
}


$requestModel       = strtolower($requestUrlParams[0]);
$requestModelId     = isset($requestUrlParams[1]) ? $requestUrlParams[1] : null;

$controllerName     = 'app\controllers\\' . ucfirst($requestModel) . 'Controller';

// Checking if controller is defined
if (!class_exists($controllerName)) {
    return Response::error("Model [$requestModel] not defined", Response::HTTP_BAD_REQUEST);
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
