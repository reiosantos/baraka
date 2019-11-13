<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use Doctrine\ORM as ORM;
use App\Utils\IRequest;
use Exception;

interface Controller
{
    /**
     * @param IRequest $request
     * @return mixed
     */
    public function processRequest(IRequest $request);

    /**
     * @param IRequest $request
     * @return ORM\Mapping\Entity[]|array|object|null
     */
    public function get(IRequest $request);

    /**
     * @param IRequest $request
     * @return mixed
     * @throws ORM\ORMException
     * @throws ORM\EntityNotFoundException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws Exception
     */
    public function post(IRequest $request);

    public function put(IRequest $request);

    /**
     * @param IRequest $request
     * @return bool
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     */
    public function delete(IRequest $request): bool;
}
