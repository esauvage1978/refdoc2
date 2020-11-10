<?php

namespace App\Repository;

use App\Entity\Process;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Process|null find($id, $lockMode = null, $lockVersion = null)
 * @method Process|null findOneBy(array $criteria, array $orderBy = null)
 * @method Process[]    findAll()
 * @method Process[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProcessRepository extends ServiceEntityRepository
{
    const ALIAS = 'p';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Process::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                MProcessRepository::ALIAS,
                UserRepository::ALIAS_P_V,
                UserRepository::ALIAS_P_C
            )
            ->leftJoin(self::ALIAS . '.mProcess', MProcessRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.validators', UserRepository::ALIAS_P_V)
            ->leftJoin(self::ALIAS . '.contributors', UserRepository::ALIAS_P_C)
            ->orderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
