<?php

namespace Tfis;

class Request
{
    public $url;
    public $port;
    public $scheme;
    public $queryString;
    public $query;
    public $method;
    public $host;
    public $status;
    public $requestUri;
    /** $requestUri - $queryString */
    public $uri;
    
    public function __construct($server)
    {
        $this->method = $server["REQUEST_METHOD"];
        $this->scheme = $server["REQUEST_SCHEME"];
        $this->host = $server["HTTP_HOST"];
        $this->port = intval($server["SERVER_PORT"]);
        $this->queryString = $server["QUERY_STRING"];
        parse_str($this->queryString, $this->query);
        $this->status = intval($server["REDIRECT_STATUS"]);
        $this->requestUri = $server["REQUEST_URI"];
        $this->url = sprintf("%s://%s%s", $this->scheme,
            $this->host, $this->requestUri);
        $end = strpos($this->requestUri, "?");
        $this->uri = $end? substr($this->requestUri, 0, $end):
                  $this->requestUri;
    }

    /**
     * get request value from $key
     * @parm string $key
     * @return string|null
     */
    public function input($key)
    {
        if (array_key_exists($key, $this->query)) {
            return $this->query[$key];
        } else {
            return null;
        }
    }
}