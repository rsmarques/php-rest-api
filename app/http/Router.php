<?php

namespace app\http;

// Class with routing/dispatching requests purpose
class Router
{
    // allowed methods
    protected $routes   = [
        'GET'       => [],
        'POST'      => [],
        'PUT'       => [],
        'DELETE'    => [],
        'ANY'       => [],
    ];

    // patterns for regex routing matching
    const REGVAL        = '/({\w+\??})\//';
    public $patterns    = [
        'mandatory' => '\w+\/',
        'optional'  => '(\w+\/)?',
    ];

    public function any($path, $handler)
    {
        $this->addRoute('ANY', $path, $handler);
    }

    public function get($path, $handler)
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post($path, $handler)
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put($path, $handler)
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete($path, $handler)
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    protected function addRoute($method, $path, $handler)
    {
        array_push($this->routes[$method], [$path => $handler]);
    }

    public function match(Request $request)
    {
        $requestMethod  = $request->getMethod();
        $requestPath    = $request->getPath();

        if (!in_array($requestMethod, array_keys($this->routes))) {
            return Response::error("Invalid URL! Usage: /{modelName}/{id?}; Methods: GET, POST, PUT, DELETE", Response::HTTP_METHOD_NOT_ALLOWED);
        }

        $routes         = array_merge($this->routes[$requestMethod], $this->routes['ANY']);

        foreach ($routes as $resource) {

            $args           = [];
            $route          = key($resource);
            $handler        = reset($resource);

            // normalizing routes beggining/end slashes
            $requestPath    = '/' . trim($requestPath, '/') . '/';
            $route          = '/' . trim($route, '/') . '/';

            if (preg_match(self::REGVAL, $route)) {
                list($args, $uri, $route) = $this->parseRegexRoute($requestPath, $route);
            }

            if (!preg_match("#^$route$#", $requestPath)) {
                continue;
            }

            // MVC-style routing (Controller@method)
            if (is_string($handler) && strpos($handler, '@')) {

                list($ctrl, $method)    = explode('@', $handler);
                $controllerName         = 'app\controllers\\' . $ctrl;
                $controller             = new $controllerName;

                return call_user_func_array(array($controller, $method), $args);
            }

            if (empty($args)) {
                return $handler();
            }

            return call_user_func_array($handler, $args);

        }

        // Route not found, sending HTTP_NOT_FOUND
        return Response::error("Invalid URL! Usage: /{modelName}/{id?}; Methods: GET, POST, PUT, DELETE", Response::HTTP_NOT_FOUND);
    }

    protected function parseRegexRoute($requestPath, $resource)
    {
        $route  = preg_replace_callback(self::REGVAL, function ($matches) use ($resource) {

            $matches[0]     = str_replace(['{', '}'], '', $matches[0]);
            // if pattern ends with ? parameter is optional
            $pattern        = substr($matches[0], -2, 1) === '?' ? $this->patterns['optional'] : $this->patterns['mandatory'];

            return $pattern;

        }, $resource);

        $regUri     = explode('/', $resource);
        $args       = array_diff(array_replace($regUri, explode('/', $requestPath)), $regUri);

        return [array_values($args), $resource, $route];
    }
}
