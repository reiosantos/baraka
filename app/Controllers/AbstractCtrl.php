<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Entity\Artist;
use App\Entity\Feedback;
use App\Entity\Song;
use App\Utils\IRequest;
use Doctrine\Migrations\Generator\Exception\InvalidTemplateSpecified;
use Exception;
use RuntimeException;
use Twig\Error as TwigError;

abstract class AbstractCtrl implements Controller
{
    public $db;
    public $entityName;
    public $sortColumn = 'ID';
    public $isAscending = true;
    public $dataHolder;
    private $twig;
    public $template;
    public $request;

    public function __construct()
    {
        global $database;
        global $twig;
        global $request;
        $this->db = $database;
        $this->twig = $twig;
        $this->request = $request;
    }

    /**
     * @return array
     * @throws Exception
     */
    private function getResponseData(): array
    {
        return [
            'songs' => $this->db->findAll(Song::class, 'name'),
            'artists' => $this->db->findAll(Artist::class, 'name'),
            'feedback' => $this->db->findAll(Feedback::class, 'upload_date'),
        ];
    }

    /**
     * @param string $template
     * @param array $context
     * @return string|null
     * @throws TwigError\LoaderError
     * @throws TwigError\RuntimeError
     * @throws TwigError\SyntaxError
     * @throws Exception
     */
    final public function render(?string $template = null, array $context = []): ?string
    {
        if (!$template || $template === null) {
            $template = $this->template;
        }
        if (!$template) {
            throw InvalidTemplateSpecified::notFoundOrNotReadable($template);
        }
        $essentialData = [
            'csrf_token' => $this->request->generateToken()
        ];

        $merged = array_merge($this->getResponseData(), $context, $essentialData);
        $this->twig->display($template, $merged);
        return null;
    }

    /**
     * @param IRequest $request
     * @return mixed|string|null
     * @throws TwigError\LoaderError
     * @throws TwigError\RuntimeError
     * @throws TwigError\SyntaxError
     */
    final public function processRequest(IRequest $request)
    {
        try {
            $method = $request->getRequestMethod();
            $action = $request->getAction();

            if ($method === 'post') {
                $request->validateToken();
            }

            if ($action && method_exists($this, $action)) {
                return $this->{$action}($request);
            }
            if (method_exists($this, $method)) {
                return $this->{$method}($request);
            }
            throw new RuntimeException('Method/Action `' . $method . '` not implemented.');
        } catch (Exception $e) {
            return $this->render(null, ['error' => $e->getMessage()]);
        }
    }

    public function get(IRequest $request) {
        if ($this->entityName === null) {
            return $this->render();
        }

        $pk = $request->getObjectPk();
        if ($pk === null) {
            $data = $this->db->findAll($this->entityName, $this->sortColumn, $this->isAscending);
        } else {
            $data = $this->db->findOneAndReturnArray($this->entityName, $pk);
            if ($data === null) {
                throw new RuntimeException('No Result Found');
            }
        }
        if ($this->template) {
            return $this->render($this->template, [$this->dataHolder => $data]);
        }
        return $data;
    }

    public function delete(IRequest $request): bool
    {
        $pk = $request->getObjectPk();
        $object = $this->db->find($this->entityName, $pk);
        if ($object === null) {
            throw new RuntimeException('No Result Found For Deletion');
        }
        $this->db->delete($object);
        $this->db->flush($object);
        $request->redirectToHome();
        return $this->render(null, ['success' => 'Deletion Successful']);
    }

    public function post(IRequest $request)
    {
        throw new RuntimeException('Method Not Implemented.');
    }

    public function put(IRequest $request)
    {
        throw new RuntimeException('Method Not Implemented.');
    }
}
