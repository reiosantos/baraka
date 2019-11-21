<?php /** @noinspection MissingParameterTypeDeclarationInspection */

/**
 * Created by PhpStorm.
 * User: reiosantos
 * Date: 1/4/18
 * Time: 10:30 PM
 */
namespace App\Database;


use Doctrine\ORM as ORM;
use Doctrine\ORM\QueryBuilder;

class Database
{
	private $entityManager;

	public function __construct()
	{
        global $entityManager;
	    $this->entityManager = $entityManager;
	}

    /**
     * Finds an Entity by its identifier.
     *
     * @param string       $entityName  The class name of the entity to find.
     * @param mixed        $id          The identity of the entity to find.
     * @param integer|null $lockMode    One of the \Doctrine\DBAL\LockMode::* constants
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
    public function find(string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?object {
	    return $this->entityManager->find($entityName, $id, $lockMode, $lockVersion);
    }

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
    public function findOneAndReturnArray(
        string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?array
    {
        $data = $this->find($entityName, $id, $lockMode, $lockVersion);
        if (!$data) {
            return $data;
        }
        if (is_array($data)) {
            return $data;
        }
        return [$data];
    }

    /**
     * @param string $entityName
     * @return array
     */
    public function findAll(string $entityName): array
    {
        return $this->getRepository($entityName)
            ->createQueryBuilder('c')
            ->getQuery()
            ->getResult(ORM\Query::HYDRATE_OBJECT);
    }

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
    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

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
    public function flush($entity = null): void
    {
        $this->entityManager->flush($entity);
    }

    /**
     * * Gets the repository for an entity class.
     * @param string $entityName The name of the entity.
     * @return ORM\EntityRepository
     */
    public function getRepository(string $entityName): ORM\EntityRepository {
        return $this->entityManager->getRepository($entityName);
    }

    /**
     * @return ORM\EntityManagerInterface
     */
    private function getEntityManager(): ORM\EntityManagerInterface {
        return $this->entityManager;
    }
    /**
     * @param object $object
     */
    public function delete(object $object): void
    {
        $this->getEntityManager()->remove($object);
    }

    /**
     * @param string $entityName
     * @param array $predicate ['like' => [], 'and' => [], 'or' => []]
     * @return array
     */
    public function search(string $entityName, array $predicate): array
    {
        $qb = $this->entityManager->createQueryBuilder()
            ->select(['u'])
            ->from($entityName, 'u');

        $parameters = [];
        if (array_key_exists('like', $predicate)) {
            $qb = $this->addFilter($qb, 'where', $predicate['like'], 'LOWER(u.%s) LIKE LOWER(:%s)');
            $parameters = array_merge($parameters, $predicate['like']);
        }
        if (array_key_exists('and', $predicate)) {
            $qb = $this->addFilter($qb, 'andWhere', $predicate['and'], 'LOWER(u.%s) = LOWER(:%s)');
            $parameters = array_merge($parameters, $predicate['and']);
        }
        if (array_key_exists('or', $predicate)) {
            $qb = $this->addFilter($qb, 'orWhere', $predicate['or'], 'LOWER(u.%s) = LOWER(:%s)');
            $parameters = array_merge($parameters, $predicate['or']);
        }
        $qb = $qb->setParameters($parameters)->addOrderBy('u.id', 'ASC');
        $query = $qb->getQuery();
        return $query->getResult(ORM\Query::HYDRATE_OBJECT);
    }

    /**
     * @param QueryBuilder $qb
     * @param string $fnName
     * @param array $values
     * @param string $query
     * @return QueryBuilder
     */
    private function addFilter(QueryBuilder $qb, string $fnName, array $values, string $query): QueryBuilder
    {
        foreach ($values as $key => $value) {
            $qb = $qb->{$fnName}(str_replace('%s', $key, $query));
        }
        return $qb;
    }
}
