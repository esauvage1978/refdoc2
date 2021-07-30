<?php

namespace App\Repository;

use App\Entity\BackpackFileSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackpackFileSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackpackFileSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackpackFileSource[]    findAll()
 * @method BackpackFileSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackFileSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackpackFileSource::class);
    }

    // /**
    //  * @return BackpackFileSource[] Returns an array of BackpackFileSource objects
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
    public function findOneBySomeField($value): ?BackpackFileSource
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
