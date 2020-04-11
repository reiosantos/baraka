<?php /** @noinspection MissingReturnTypeInspection */

namespace App\Controllers;

use App\Utils\IRequest;
use Doctrine\ORM as ORM;
use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

interface Controller
{
    /**
     * @param IRequest $request
     * @return mixed
     */
    public function processRequest(IRequest $request);

    /**
     * @param IRequest $request
     * @return array|ORM\Mapping\Entity[]|array|object|null
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     */
    public function get(IRequest $request);

    /**
     * @param IRequest $request
     * @return mixed
     * @throws ORM\ORMException
     * @throws ORM\EntityNotFoundException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function post(IRequest $request);

    /**
     * @param IRequest $request
     * @return mixed
     * @throws ORM\ORMException
     * @throws ORM\EntityNotFoundException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function put(IRequest $request);

    /**
     * @param IRequest $request
     * @return mixed
     * @throws ORM\ORMException
     * @throws ORM\EntityNotFoundException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function update(IRequest $request);

    /**
     * @param IRequest $request
     * @return string
     * @throws ORM\ORMException
     * @throws ORM\OptimisticLockException
     * @throws ORM\TransactionRequiredException
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function delete(IRequest $request): ?string;
}
