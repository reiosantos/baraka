<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Utils\IRequest;
use RuntimeException;
use Doctrine\ORM as ORM;

abstract class AbstractCtrl implements Controller
{
    public $db;
    public $entityName;

    public function __construct()
    {
        global $database;
        $this->db = $database;
    }

    /**
     * @param IRequest $request
     * @return mixed
     */
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

    /**
     * @param IRequest $request
     * @return ORM\Mapping\Entity[]|array|object|null
     */
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

    /**
     * @param IRequest $request
     * @return bool
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     */
    public function delete(IRequest $request): bool
    {
        $pk = $request->getObjectPk();
        $object = $this->db->find($this->entityName, $pk);
        if ($object === null) {
            throw new RuntimeException('No Result Found For Deletion');
        }
        $this->db->delete($object);
        return true;
    }
}
