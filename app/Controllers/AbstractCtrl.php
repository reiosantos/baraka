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
        if (method_exists($this, $method)) {
            return $this->{$method}($request);
        }
        throw new RuntimeException('Method `'. $method .'` not implemented.');
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
        return $this->db->getRepository($this->entityName)->find($pk);
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
            throw new ORM\NoResultException();
        }
        $this->db->delete($object);
        return true;
    }
}
