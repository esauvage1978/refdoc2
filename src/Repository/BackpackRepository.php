<?php

namespace App\Repository;

use App\Entity\Backpack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Backpack|null find($id, $lockMode = null, $lockVersion = null)
 * @method Backpack|null findOneBy(array $criteria, array $orderBy = null)
 * @method Backpack[]    findAll()
 * @method Backpack[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BackpackRepository extends ServiceEntityRepository
{
    const ALIAS = 'b';
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backpack::class);
    }


    public function findAllFillComboboxDir1(string $idMp, string $idP)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.dir1 as id ,' . self::ALIAS . '.dir1 as name')
            ->Where(self::ALIAS . '.mProcess = :mp')
            ->andWhere(self::ALIAS . '.dir1 is not null');

        if ($idP !== "0") {
            $builder
                ->andWhere(self::ALIAS . '.process = :p')
                ->setParameters(['mp' => $idMp, 'p' => $idP]);
        } else {
            $builder
                ->andWhere(self::ALIAS . '.process is null')
                ->setParameters(['mp' => $idMp]);
        }

        $builder->orderBy(self::ALIAS . '.dir1', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }

    public function findCountForRefPattern(string $refPattern)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(\'ref\')');

        $builder
            ->Where(self::ALIAS . '.stateCurrent = \'published\'')
            ->andWhere(self::ALIAS . '.ref like :pattern')
            ->setParameter('pattern', '%' . $refPattern . '%');

        return $builder->getQuery()->getSingleScalarResult();
    }
    public function findCountForRefPatternCheck(string $refPattern,$id)
    {
        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(\'ref\')');

        $builder
            ->Where(self::ALIAS . '.stateCurrent = \'published\'')
            ->andWhere(self::ALIAS . '.ref like :pattern')
            ->andWhere(self::ALIAS . '.id != :id')
            ->setParameters(['pattern'=> '%' . $refPattern . '%','id'=>$id]);

        return $builder->getQuery()->getSingleScalarResult();
    }

    public function findAllFillComboboxDirOther(string $idMp, string $idP, string $data, int $numDir)
    {
        $dirCourant = 'dir' . $numDir;
        $dirPrecedent = 'dir' . ($numDir - 1);

        $builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.' . $dirCourant . ' as id ,' . self::ALIAS . '.' . $dirCourant . ' as name');

        $builder = $builder
            ->Where(self::ALIAS . '.mProcess = :mp')
            ->andWhere(self::ALIAS . '.' . $dirPrecedent . ' = :dirp')
            ->andWhere(self::ALIAS . '.' . $dirCourant . ' is not null');

        if ($idP !== "0") {
            $builder
                ->andWhere(self::ALIAS . '.process = :p')
                ->setParameters(['mp' => $idMp, 'p' => $idP,  'dirp' => $data]);
        } else {
            $builder
                ->andWhere(self::ALIAS . '.process is null')
                ->setParameters(['mp' => $idMp,  'dirp' => $data]);
        }

        $builder->orderBy(self::ALIAS . '.' . $dirCourant . '', 'ASC');

        return $builder
            ->getQuery()
            ->getResult();
    }


}
