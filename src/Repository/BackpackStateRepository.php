<?php

namespace App\Repository;

use App\Entity\BackpackState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackpackState|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackpackState|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackpackState[]    findAll()
 * @method BackpackState[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackStateRepository extends ServiceEntityRepository
{
    const ALIAS = 'ast';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackpackState::class);
    }

    public function findAllForBackpack(string $backpackId)
    {
        return $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                BackpackRepository::ALIAS,
                UserRepository::ALIAS)
            ->join( self::ALIAS.'.backpack', BackpackRepository::ALIAS)
            ->join(self::ALIAS.'.user', UserRepository::ALIAS)
            ->where(self::ALIAS.'.backpack = :backpack')
            ->setParameter('backpack', $backpackId)
            ->orderBy( self::ALIAS.'.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
