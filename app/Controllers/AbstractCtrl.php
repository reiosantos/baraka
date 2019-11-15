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
    public $dataHolder;
    private $twig;
    public $template;

    public function __construct()
    {
        global $database;
        global $twig;
        $this->db = $database;
        $this->twig = $twig;
    }

    private function getResponseData(): array
    {
        return [
            'songs' => $this->db->getRepository(Artist::class)->findAll(),
            'artists' => $this->db->getRepository(Song::class)->findAll(),
            'feedback' => $this->db->getRepository(Feedback::class)->findAll(),
        ];
    }

    /**
     * @param string $template
     * @param array $context
     * @return string|null
     * @throws TwigError\LoaderError
     * @throws TwigError\RuntimeError
     * @throws TwigError\SyntaxError
     */
    final public function render(?string $template = null, array $context = []): ?string
    {
        if (!$template || $template === null) {
            $template = $this->template;
        }
        if (!$template) {
            throw InvalidTemplateSpecified::notFoundOrNotReadable($template);
        }
        $merged = array_merge($this->getResponseData(), $context);
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
        $pk = $request->getObjectPk();
        if ($pk === null) {
            $data = $this->db->getRepository($this->entityName)->findAll();
        } else {
            $data = $this->db->getRepository($this->entityName)->find($pk);
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

    public function put(IRequest $request)
    {
        return $this->render($this->template);
    }
}
