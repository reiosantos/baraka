<?php /** @noinspection GlobalVariableUsageInspection */

namespace App\Utils;


/** @noinspection ClassNameCollisionInspection */

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

    public function __construct()
    {
        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->populateRequestObject();
    }

    private function populateRequestObject(): void
    {
        if (array_key_exists('REQUEST_METHOD', $this->server)) {
            $this->method = strtolower($this->server['REQUEST_METHOD']);
        }
        if (array_key_exists('QUERY_STRING', $this->server)) {
            $this->queryString = $this->server['QUERY_STRING'];
        }
        $this->host = $this->server['HTTP_HOST'];
        $this->requestUri = explode('?', $this->server['REQUEST_URI'], 2)[0];
    }

    public function redirectToHome(?string $to = null): void
    {
        if ($to && $to !== null) {
            header('Location: /' . $to . '/');
            return;
        }
        header('Location: /' . $this->getControllerName() . '/');
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
        // in the form /controller/pk/
        $action = explode('/', $this->requestUri);
        if (count($action) <= 1 || $action[1] === '') {
            return 'song';
        }
        return $action[1];

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getAction(): ?string
    {
        $attr = $this->getRequestURIAttributes();
        if (count($attr) < 2) {
            return null;
        }
        return $attr[1];
    }

    public function getRequestURIAttributes(): ?array
    {
        // This is used if the application is running in docker and/or the .htaccess is
        // working/ is able to resolve the path names
        // in the form /controller/pk/
        $uri = explode('/', $this->requestUri);
        return array_slice($uri, 2);

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getObjectPk(): ?string
    {
        $attr = $this->getRequestURIAttributes();
        if (count($attr) === 0) {
            return null;
        }
        return $attr[0];
    }

    public function get(string $param, ?string $default = null): ?string
    {
        if (property_exists($this, $param)) {
            return $this->{$param};
        }
        $vars = [
            $this->request,
            $this->server
        ];
        foreach ($vars as $obj) {
            if (array_key_exists($param, $obj)) {
                return $this->cleanData($obj[$param]);
            }
        }
        return $default;
    }

    public function getFile(string $param): ?array
    {
        if (array_key_exists($param, $this->files)) {
            return $this->files[$param];
        }
        return null;
    }

    public function getFilesArray(): ?array
    {
         return $this->files;
    }

    /**
     * @param string $data
     * @return string
     */
    public function cleanData(string $data): string
    {
        return htmlentities(htmlspecialchars($data), ENT_QUOTES);
    }
}
