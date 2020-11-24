<?php

namespace App\Repository;

use App\Entity\BackpackFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BackpackFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackpackFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackpackFile[]    findAll()
 * @method BackpackFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackFileRepository extends ServiceEntityRepository
{
    const ALIAS="bfs";
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackpackFile::class);
    }
}
