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
    public const ALIAS = 'mp';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProcess::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS
            )
            ->orderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    } 
}
