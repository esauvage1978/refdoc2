<?php

namespace App\Repository;

use App\Entity\MProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MProcess|null find($id, $lockMode = null, $lockVersion = null)
 * @method MProcess|null findOneBy(array $criteria, array $orderBy = null)
 * @method MProcess[]    findAll()
 * @method MProcess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MProcessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProcess::class);
    }

    // /**
    //  * @return MProcess[] Returns an array of MProcess objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MProcess
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
