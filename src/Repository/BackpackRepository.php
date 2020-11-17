<?php

namespace App\Repository;

use App\Entity\Backpack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Backpack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Backpack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Backpack[]    findAll()
 * @method Backpack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backpack::class);
    }

    // /**
    //  * @return Backpack[] Returns an array of Backpack objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Backpack
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
