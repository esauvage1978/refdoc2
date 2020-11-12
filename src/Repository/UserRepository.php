<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\SubscriptionRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public const ALIAS = 'u';
    public const ALIAS_MP_DV = 'u_mp_dv';
    public const ALIAS_MP_PV = 'u_mp_pv';
    public const ALIAS_MP_C = 'u_mp_c';
    public const ALIAS_P_V = 'u_p_v';
    public const ALIAS_P_C = 'u_p_c';

    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllForAdmin()
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                SubscriptionRepository::ALIAS,
                UserParamRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.userParam', UserParamRepository::ALIAS)
            ->leftJoin(self::ALIAS.'.subscriptions',SubscriptionRepository::ALIAS)
            ->orderBy(self::ALIAS . '.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
  
}
