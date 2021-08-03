<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DtoInterface;
use App\Dto\MProcessDto;
use App\Entity\MProcess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

use function count;

class MProcessDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /** @var MProcessDto */
    private $dto;

    public const FILTRE_DTO_INIT_HOME = 'home';
    public const FILTRE_DTO_INIT_TABLEAU = 'tableau';
    public const FILTRE_DTO_INIT_SEARCH = 'search';
    public const FILTRE_DTO_INIT_SUBSCRIPTION = 'subscription';
    public const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    private $type_order="all";

    public const ALIAS = 'r';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MProcess::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->type_order='count';

        $this->initialise_selectCount();

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findForCombobox(DtoInterface $dto)
    {
        $this->dto = $dto;

        $this->initialise_selectCombobox();

        $this->type_order='combo';

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
        $this->dto = $dto;

        $this->initialise_selectAll();

        $this->initialise_where();

        $this->initialise_orderBy();

        if (empty($page)) {
            $this->builder
                ->getQuery()
                ->getResult();
        } else {
            $this->builder
                ->setFirstResult(($page - 1) * $limit)
                ->setMaxResults($limit);
        }

        return new Paginator($this->builder);
    }

    public function findAllForDto(DtoInterface $dto, string $filtre = self::FILTRE_DTO_INIT_HOME)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        switch ($filtre) {
            case self::FILTRE_DTO_INIT_TABLEAU:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_selectAll();
                break;
            case self::FILTRE_DTO_INIT_SUBSCRIPTION:
                $this->initialise_selectSubscription();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_select_home(): void
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ProcessRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS);
    }

    private function initialise_selectAll(): void
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ProcessRepository::ALIAS
            )
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS);
    }

    private function initialise_selectSubscription(): void
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                ProcessRepository::ALIAS,
                SubscriptionRepository::ALIAS,
                UserRepository::ALIAS
            )
            ->innerJoin(self::ALIAS . '.subscriptions', SubscriptionRepository::ALIAS)
            ->innerJoin(SubscriptionRepository::ALIAS . '.user', UserRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS);
    }

    private function initialise_selectCombobox(): void
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('distinct ' . self::ALIAS . '.id, concat(' . self::ALIAS . '.ref,\' - \',' . self::ALIAS . '.name) as name')
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS);
    }

    private function initialise_selectCount(): void
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->leftJoin(self::ALIAS . '.processes', ProcessRepository::ALIAS);
    }

    private function initialise_where(): void
    {
        $this->params = [];
        $dto = $this->dto;
        $this->builder
            ->where(self::ALIAS . '.id>0');

        $this->initialise_where_enable();

        $this->initialise_where_user_can_update();

        $this->initialise_where_search();

        $this->initialise_where_subscription();

        if (count($this->params) <= 0) {
            return;
        }

        $this->builder->setParameters($this->params);
    }


    private function initialise_where_user_can_update()
    {
        if (!empty($this->dto->getForUpdate())) {
            $u = $this->dto->getUserDto();
            if (!empty($u) && !empty($u->getId())) {

                $qWC = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.contributors', UserRepository::ALIAS_MP_C)
                    ->where(UserRepository::ALIAS_MP_C . '.id= :idUser');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(
                        self::ALIAS . '.id IN (' . $qWC->getDQL() . ')'   );
            }
        }
    }

    private function initialise_where_subscription(): void
    {
        $t = $this->dto->getSubscriptionDto();
        if (empty($t) || empty($t->getUserDto()->getId())) {
            return;
        }

        $this->builder->andWhere(UserRepository::ALIAS . '.id = :subid');
        $this->builder->andWhere(SubscriptionRepository::ALIAS . '.isEnable = :subIE');
        $this->addParams('subid', $t->getUserDto()->getId());
        $this->addParams('subIE', true);
    }

    private function initialise_where_enable(): void
    {
        if (!empty($this->dto->getVisible())) {
            $this->builder->andWhere(self::ALIAS . '.isEnable= true');
        } elseif (!empty($this->dto->getHide())) {
            $this->builder->andWhere(self::ALIAS . '.isEnable= false');
        } else {
            $e = $this->dto->getIsEnable();
            if (!empty($e)) {
                if ($e === MProcessDto::TRUE) {
                    $this->builder->andWhere(self::ALIAS . '.isEnable= true');
                } elseif ($e === MProcessDto::FALSE) {
                    $this->builder->andWhere(self::ALIAS . '.isEnable= false');
                }
            }

            $e = $this->dto->getProcessDto();
            if (!empty($e)) {
                if ($e->getIsEnable() === MProcessDto::TRUE) {
                    $this->builder->andWhere(ProcessRepository::ALIAS . '.isEnable= true');
                } elseif ($e->getIsEnable() === MProcessDto::FALSE) {
                    $this->builder->andWhere(ProcessRepository::ALIAS . '.isEnable= false');
                }
            }
        }
    }

    private function initialise_where_search(): void
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (empty($dto->getWordSearch())) {
            return;
        }

        $builder
            ->andWhere(
                self::ALIAS . '.content like :search' .
                    ' OR ' . self::ALIAS . '.name like :search' .
                    ' OR ' . self::ALIAS . '.ref like :search' .
                    ' OR ' . ProcessRepository::ALIAS . '.ref like :search' .
                    ' OR ' . ProcessRepository::ALIAS . '.grouping like :search' .
                    ' OR ' . ProcessRepository::ALIAS . '.name like :search'
            );

        $this->addParams('search', '%' . $dto->getWordSearch() . '%');
    }

    private function initialise_orderBy(): void
    {
        $this->builder
            ->orderBy(self::ALIAS . '.showOrder', 'ASC')
            ->addOrderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC')
            ->addOrderBy(ProcessRepository::ALIAS . '.grouping', 'ASC')
            ->addOrderBy(ProcessRepository::ALIAS . '.ref', 'ASC')
            ->addOrderBy(ProcessRepository::ALIAS . '.name', 'ASC');
    }

    private function initialise_orderByName(): void
    {
        $this->builder
            ->orderBy(self::ALIAS . '.name', 'ASC');
    }
}
