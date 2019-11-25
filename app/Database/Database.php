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

class Database implements IDatabase
{
	private $entityManager;

	public function __construct()
	{
        global $entityManager;
	    $this->entityManager = $entityManager;
	}

    /**
     * {@inheritDoc}
     */
    public function find(string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?object {
	    return $this->entityManager->find($entityName, $id, $lockMode, $lockVersion);
    }

    /**
     * {@inheritDoc}
     */
    public function findOneAndReturnArray(
        string $entityName, $id, int $lockMode = null, int $lockVersion = null): ?array
    {
        $data = $this->find($entityName, $id, $lockMode, $lockVersion);
        if (!$data) {
            return null;
        }
        if (is_array($data)) {
            return $data;
        }
        return [$data];
    }

    /**
     * {@inheritDoc}
     */
    public function findAll(string $entityName, string $orderBy = null, bool $isAsc = true): array
    {
        $orderBy = $orderBy ?? 'ID';
        $order = $isAsc ? 'ASC' : 'DESC';

        return $this->getRepository($entityName)
            ->createQueryBuilder('c')
//            ->addOrderBy("c.$orderBy", $order)
            ->getQuery()
            ->getResult(ORM\Query::HYDRATE_OBJECT);
    }

    /**
     * {@inheritDoc}
     */
    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function flush($entity = null): void
    {
        $this->entityManager->flush($entity);
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     */
    public function delete(object $object): void
    {
        $this->getEntityManager()->remove($object);
    }

    /**
     * {@inheritDoc}
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
