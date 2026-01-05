<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    //    /**
    //     * @return Item[] Returns an array of Item objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Item
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function getPaginatedQueryBuilderForInventory(int $inventoryId): QueryBuilder
    {
        return $this->createQueryBuilder('i')
            ->where('i.inventory = :inventoryId')
            ->setParameter('inventoryId', $inventoryId)
            ->orderBy('i.createdAt', 'DESC');
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('i')
            ->leftJoin('i.inventory', 'inv')
            ->where('i.customId LIKE :query')
            ->orWhere('inv.title LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
