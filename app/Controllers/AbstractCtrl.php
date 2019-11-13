<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Utils\IRequest;
use RuntimeException;

abstract class AbstractCtrl implements Controller
{
    public $db;
    public $entityName;

    public function __construct()
    {
        global $database;
        $this->db = $database;
    }

    final public function processRequest(IRequest $request)
    {
        $method = $request->getRequestMethod();
        $action = $request->getAction();

        if ($action && method_exists($this, $action)) {
            return $this->{$action}($request);
        }
        if (method_exists($this, $method)) {
            return $this->{$method}($request);
        }
        throw new RuntimeException('Method/Action `'. $method .'` not implemented.');
    }

    public function get(IRequest $request) {
        $pk = $request->getObjectPk();
        if ($pk === null) {
            return $this->db->getRepository($this->entityName)->findAll();
        }
        $obj = $this->db->getRepository($this->entityName)->find($pk);
        if ($obj === null) {
            throw new RuntimeException('No Result Found');
        }
        return $obj;
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
        return true;
    }
}
