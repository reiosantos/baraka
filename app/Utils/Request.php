<?php
namespace App\Utils;


/**
 * Request represents an HTTP request.
 *
 * The methods dealing with URL accept / return a raw path (% encoded):
 *   * getBasePath
 *   * getBaseUrl
 *   * getPathInfo
 *   * getRequestUri
 *   * getUri
 *   * getUriForPath
 */
class Request implements IRequest
{
    private $request;
    private $server;
    private $files;

    private $method;
    private $queryString;
    private $host;
    private $requestUri;

    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    public const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->populateRequestObject();
    }

    private function populateRequestObject(): void
    {
        $this->method = $this->server['REQUEST_METHOD'];
        $this->queryString = $this->server['QUERY_STRING'];
        $this->host = $this->server['HTTP_HOST'];
        $this->requestUri = $this->server['REQUEST_URI'];
    }

    public function getRequestMethod(): string
    {
       return $this->method;
    }

    public function getQueryString(): string{
        return $this->queryString;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getRequestUri(): string
    {
        return $this->requestUri;
    }

    public function getAction(): string
    {
        return $this->request['name'] ?? null;
    }

    public function get(string $param, ?string $default = null): ?string
    {
        if (property_exists($this, $param)) {
            return $this->{$param};
        }
        $vars = [
            $this->request,
            $this->server,
            $this->files
        ];
        foreach ($vars as $obj) {
            if (array_key_exists($param, $obj)) {
                return $obj[$param];
            }
        }
        return $default;
    }

}
