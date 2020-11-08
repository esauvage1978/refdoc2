<?php

namespace App\Repository;

use App\Entity\UserParam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserParam|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserParam|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserParam[]    findAll()
 * @method UserParam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserParamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserParam::class);
    }
}
