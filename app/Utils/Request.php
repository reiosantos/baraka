<?php /** @noinspection GlobalVariableUsageInspection */

namespace App\Utils;


use Exception;
use RuntimeException;

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
    private $entityManager;
    private $request;
    private $server;
    private $files;

    private $method;
    private $queryString;
    private $host;
    private $requestUri;

    private const TOKEN_KEY = 'csrf_token';

    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;

        $this->request = $_REQUEST;
        $this->server = $_SERVER;
        $this->files = $_FILES;

        if (!isset($_SESSION)) {
            session_start();
        }
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

    public function getContentSize(): string {
        return $this->server['CONTENT_LENGTH'] ?? '';
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

    public function getBaseUrl(): string
    {
        if ($this->getControllerName() === 'admin') {
            return '/admin/';
        }
        return '/';
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
        if (count($attr) < 3) {
            return null;
        }
        return $attr[2];
    }

    public function getRequestURIAttributes(): ?array
    {
        // This is used if the application is running in docker and/or the .htaccess is
        // working/ is able to resolve the path names
        // in the form /controller/pk/action
        // or /admin/controller/pk/action
        $uri = explode('/', $this->requestUri);
        if ($this->getControllerName() === 'admin') {
            return array_slice($uri, 2);
        }
        return array_slice($uri, 1);

        // use below if server not running in docker or the .htaccess is not working for soe reason
        // return $this->request['name'] ?? null;
    }

    public function getModelFromRequest(): ?string
    {
        $name = $this->getRequestURIAttributes();
        if (count($name) > 1) {
            return 'App\Entity\\' . ucwords($name[0]);
        }
        return null;
    }

    public function getObjectPk(): ?string
    {
        $attr = $this->getRequestURIAttributes();
        if (count($attr) <= 1) {
            return null;
        }
        return $attr[1];
    }

    public function get(string $param, ?string $default = null): ?string
    {
        if (property_exists($this, $param)) {
            return trim($this->{$param});
        }
        $vars = [
            $this->request,
            $this->server
        ];
        foreach ($vars as $obj) {
            if (array_key_exists($param, $obj)) {
                return trim($this->cleanData($obj[$param]));
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

    public function addToSession(string $key, string $value = null): void
    {
        $_SESSION[$key] = $value;
    }

    public function getFromSession(string $key): ?string
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        return null;
    }

    /**
     * @return string|null
     * @throws Exception
     */
    public function generateToken(): ?string
    {
        $tkn = bin2hex(random_bytes(32));
        $this->addToSession(self::TOKEN_KEY, $tkn);
        return $this->getFromSession(self::TOKEN_KEY);
    }

    public function validateToken(): bool
    {
        if ($this->getFromSession(self::TOKEN_KEY) !== $this->get(self::TOKEN_KEY)) {
            throw new RuntimeException('Token Expired: Form resubmission not allowed.');
        }
        return true;
    }

    public function clearOldToken(): void
    {
        $this->addToSession(self::TOKEN_KEY, null);
    }
}
