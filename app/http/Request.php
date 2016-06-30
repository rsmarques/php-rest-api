<?php

namespace app\http;

/**
 * Request represents an HTTP request (based on symfony/http-foundation).
 *
 */
class Request
{
    const METHOD_HEAD       = 'HEAD';
    const METHOD_GET        = 'GET';
    const METHOD_POST       = 'POST';
    const METHOD_PUT        = 'PUT';
    const METHOD_PATCH      = 'PATCH';
    const METHOD_DELETE     = 'DELETE';
    const METHOD_PURGE      = 'PURGE';
    const METHOD_OPTIONS    = 'OPTIONS';
    const METHOD_TRACE      = 'TRACE';
    const METHOD_CONNECT    = 'CONNECT';

    public $method;
    protected $path;
    protected $requestUri;
    protected $queryString;
    protected $content;

    public function __construct($server = [], $content = [])
    {
        $requestUri         = isset($server['REQUEST_URI']) ? $server['REQUEST_URI'] : null;
        $requestParsed      = parse_url($requestUri);

        $this->method       = isset($server['REQUEST_METHOD']) ? $server['REQUEST_METHOD'] : self::METHOD_GET;
        $this->requestUri   = $requestUri;
        $this->path         = isset($requestParsed['path']) ? $requestParsed['path'] : null;
        $this->queryString  = isset($requestParsed['query']) ? $requestParsed['query'] : null;
        $this->content      = $content;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getRequestUri()
    {
        return $this->requestUri;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function getContent()
    {
        if (empty($this->content)) {
            parse_str(file_get_contents("php://input"), $content);
            $this->content = $content;
        }

        return $this->content;
    }

    /**
     * Creates a new request with values from PHP's super globals.
     *
     * @return Request A new request
     */
    public static function createFromGlobals()
    {
        return new static($_SERVER, $_POST);
    }
}
