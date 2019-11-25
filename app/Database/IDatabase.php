<?php /** @noinspection MissingParameterTypeDeclarationInspection */

/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/4/18
 * Time: 10:30 PM
 */

namespace App\Database;


use Doctrine\ORM as ORM;

interface IDatabase
{
    /**
     * Finds an Entity by its identifier.
     *
     * @param string $entityName The class name of the entity to find.
     * @param mixed $id The identity of the entity to find.
     * @param integer|null $lockMode One of the \Doctrine\DBAL\LockMode::* constants
     *                                  or NULL if no specific lock mode should be used
     *                                  during the search.
     * @param integer|null $lockVersion The version of the entity to find when using
     *                                  optimistic locking.
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     *
     * @throws ORM\OptimisticLockException
     * @throws ORM\ORMInvalidArgumentException
     * @throws ORM\TransactionRequiredException
     * @throws ORM\ORMException
     */
    public function find(string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?object;

    /**
     * Finds an Entity by its identifier and returns it in an array.
     *
     * @param string $entityName The class name of the entity to find.
     * @param mixed $id The identity of the entity to find.
     * @param integer|null $lockMode One of the \Doctrine\DBAL\LockMode::* constants
     *                                  or NULL if no specific lock mode should be used
     *                                  during the search.
     * @param integer|null $lockVersion The version of the entity to find when using
     *                                  optimistic locking.
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     *
     * @throws ORM\OptimisticLockException
     * @throws ORM\ORMInvalidArgumentException
     * @throws ORM\TransactionRequiredException
     * @throws ORM\ORMException
     */
    public function findOneAndReturnArray(string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?array;

    /**
     * @param string $entityName
     * @param string $orderBy
     * @param bool $isAsc
     * @return array
     */
    public function findAll(string $entityName, string $orderBy = null, bool $isAsc = true): array;

    /**
     * Tells the EntityManager to make an instance managed and persistent.
     *
     * The entity will be entered into the database at or before transaction
     * commit or as a result of the flush operation.
     *
     * NOTE: The persist operation always considers entities that are not yet known to
     * this EntityManager as NEW. Do not pass detached entities to the persist operation.
     *
     * @param object $entity The instance to make managed and persistent.
     *
     * @return void
     *
     * @throws ORM\ORMInvalidArgumentException
     * @throws ORM\ORMException
     */
    public function persist(object $entity): void;

    /**
     * Flushes all changes to objects that have been queued up to now to the database.
     * This effectively synchronizes the in-memory state of managed objects with the
     * database.
     *
     * If an entity is explicitly passed to this method only this entity and
     * the cascade-persist semantics + scheduled inserts/removals are synchronized.
     *
     * @param null|object|array $entity
     *
     * @return void
     *
     * @throws ORM\OptimisticLockException If a version check on an entity that
     *         makes use of optimistic locking fails.
     * @throws ORM\ORMException
     */
    public function flush($entity = null): void;

    /**
     * * Gets the repository for an entity class.
     * @param string $entityName The name of the entity.
     * @return ORM\EntityRepository
     */
    public function getRepository(string $entityName): ORM\EntityRepository;

    /**
     * @param object $object
     */
    public function delete(object $object): void;

    /**
     * @param string $entityName
     * @param array $predicate ['like' => [], 'and' => [], 'or' => []]
     * @return array
     */
    public function search(string $entityName, array $predicate): array;
}
