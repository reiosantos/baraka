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
        if (!array_key_exists('REQUEST_METHOD', $this->server)) {
            return;
        }
        $this->method = strtolower($this->server['REQUEST_METHOD']);
        $this->queryString = $this->server['QUERY_STRING'];
        $this->host = $this->server['HTTP_HOST'];
        $this->requestUri = $this->server['REQUEST_URI'];
    }

    public function getRequestMethod(): ?string
    {
       return $this->method;
    }

    public function getQueryString(): ?string{
        return $this->queryString;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getRequestUri(): ?string
    {
        return $this->requestUri;
    }

    public function getControllerName(): ?string
    {
        // This is used if the application is running in docker and/or the .htaccess is
        // working/ is able to resolve the path names
        // in the form /controller/action/
        $action = explode('/', $this->requestUri);
        if (count($action) <= 1 || $action[1] === '') {
            return 'songs';
        }
        return $action[1];

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getAction(): ?string
    {
        // This is used if the application is running in docker and/or the .htaccess is
        // working/ is able to resolve the path names
        // in the form /controller/action/
        $action = explode('/', $this->requestUri);
        return count($action) > 1 ? $action[2] : null;

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getRequestURIAttributes(): ?array
    {
        // This is used if the application is running in docker and/or the .htaccess is
        // working/ is able to resolve the path names
        // in the form /controller/action/
        $uri = explode('/', $this->requestUri);
        return array_slice($uri, 3);

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getObjectPk(): ?string
    {
        $attr = $this->getRequestURIAttributes();
        if (count($attr) === 0) {
            return null;
        }
        return $this->getRequestURIAttributes()[0];
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
