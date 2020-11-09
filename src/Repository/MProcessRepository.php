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
                self::ALIAS,
                ProcessRepository::ALIAS,
                UserRepository::ALIAS_MP_DV,
                UserRepository::ALIAS_MP_PV,
                UserRepository::ALIAS_MP_C
            )
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.dirValidators', UserRepository::ALIAS_MP_DV)
            ->leftJoin(self::ALIAS . '.poleValidators', UserRepository::ALIAS_MP_PV)
            ->leftJoin(self::ALIAS . '.contributors', UserRepository::ALIAS_MP_C)
            ->orderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    } 
}
