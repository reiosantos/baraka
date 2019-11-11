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
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Request implements IRequest
{
    private $request;
    private $server;
    private $method;

    public const METHOD_HEAD = 'HEAD';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';
    public const METHOD_PUT = 'PUT';
    public const METHOD_PATCH = 'PATCH';
    public const METHOD_DELETE = 'DELETE';
    public const METHOD_PURGE = 'PURGE';
    private const METHOD_OPTIONS = 'OPTIONS';
    public const METHOD_TRACE = 'TRACE';
    public const METHOD_CONNECT = 'CONNECT';

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->server = $_SERVER;

        echo print_r($_REQUEST, true);
        echo print_r($_SERVER, true);
        
        $this->populateRequestObject();
    }

    private function populateRequestObject(): void
    {
        $this->method = $this->server['REQUEST_METHOD'];
        $this->host = $this->server['HTTP_HOST'];
    }

    public function getRequestMethod(): string
    {
       return $this->method;
    }

    public function getHost(): string
    {
        return $this->method;
    }

    public function get(): ?string
    {
        // TODO: Implement get() method.
    }
}
