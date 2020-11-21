<?php

namespace App\Repository;

use App\Entity\BackpackLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BackpackLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method BackpackLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method BackpackLink[]    findAll()
 * @method BackpackLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackLinkRepository extends ServiceEntityRepository
{
    const ALIAS="bls";
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BackpackLink::class);
    }

}
