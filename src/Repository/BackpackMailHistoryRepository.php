<?php

namespace App\Repository;

use App\Entity\BackpackMailHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BackpackMailHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackpackMailHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackpackMailHistory[]    findAll()
 * @method BackpackMailHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackMailHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackpackMailHistory::class);
    }

  
}
