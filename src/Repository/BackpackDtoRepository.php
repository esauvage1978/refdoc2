<?php


namespace App\Repository;


use DateTime;
use App\Dto\RubricDto;
use App\Dto\ProcessDto;
use App\Dto\BackpackDto;
use App\Dto\MProcessDto;
use App\Entity\Backpack;
use App\Entity\MProcess;
use App\Dto\DtoInterface;
use App\Entity\BackpackLink;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bundle\MakerBundle\Validator;

class BackpackDtoRepository extends ServiceEntityRepository implements DtoRepositoryInterface
{
    use TraitDtoRepository;

    /**
     * @var BackpackDto
     */
    private $dto;

    const ALIAS = 'b';

    const FILTRE_DTO_INIT_HOME = 'home';
    const FILTRE_DTO_INIT_TREE = 'tree';
    const FILTRE_DTO_INIT_SEARCH = 'search';
    const FILTRE_DTO_INIT_UNITAIRE = 'unitaire';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Backpack::class);
    }

    public function countForDto(DtoInterface $dto)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_selectCount();

        $this->initialise_where();

        return $this->builder
            ->getQuery()->getSingleScalarResult();
    }

    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null)
    {
        /**
         * var ContactDto
         */
        $this->dto = $dto;

        $this->initialise_select();

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
            case self::FILTRE_DTO_INIT_TREE:
                $this->initialise_select_tree();
                break;
            case self::FILTRE_DTO_INIT_UNITAIRE:
                $this->initialise_select();
                break;
            case self::FILTRE_DTO_INIT_HOME:
                $this->initialise_select_home();
                break;
            case self::FILTRE_DTO_INIT_SEARCH:
                $this->initialise_select();
                break;
        }

        $this->initialise_where();

        $this->initialise_orderBy();

        return $this->builder
            ->getQuery()
            ->getResult();
    }

    private function initialise_select_home()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                MProcessRepository::ALIAS,
                ProcessRepository::ALIAS
            )
            ->join(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->join(self::ALIAS . '.mProcess', MProcessRepository::ALIAS)
            ->leftjoin(self::ALIAS . '.process', ProcessRepository::ALIAS);
    }

    private function initialise_select_tree()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                MProcessRepository::ALIAS,
                ProcessRepository::ALIAS,
                BackpackFileRepository::ALIAS,
                BackpackLinkRepository::ALIAS,
                UserRepository::ALIAS
            )
            ->join(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->join(self::ALIAS . '.mProcess', MProcessRepository::ALIAS)
            ->join(self::ALIAS . '.owner', UserRepository::ALIAS)
            ->leftjoin(self::ALIAS . '.process', ProcessRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.backpackFiles', BackpackFileRepository::ALIAS)
            ->leftJoin(self::ALIAS . '.backpackLinks', BackpackLinkRepository::ALIAS);
    }

    private function initialise_select()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select(
                self::ALIAS,
                CategoryRepository::ALIAS,
                MProcessRepository::ALIAS,
                ProcessRepository::ALIAS,
                UserRepository::ALIAS
            )
            ->join(self::ALIAS . '.owner', UserRepository::ALIAS)
            ->join(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->join(self::ALIAS . '.mProcess', MProcessRepository::ALIAS)
            ->leftjoin(self::ALIAS . '.process', ProcessRepository::ALIAS)
            ->leftjoin(self::ALIAS . '.process', ProcessRepository::ALIAS);
    }

    private function initialise_selectCount()
    {
        $this->builder = $this->createQueryBuilder(self::ALIAS)
            ->select('count(distinct ' . self::ALIAS . '.id)')
            ->join(self::ALIAS . '.category', CategoryRepository::ALIAS)
            ->join(self::ALIAS . '.owner', UserRepository::ALIAS)
            ->join(self::ALIAS . '.mProcess', MProcessRepository::ALIAS)
            ->leftjoin(self::ALIAS . '.process', ProcessRepository::ALIAS);
    }

    private function initialise_where()
    {
        $this->params = [];
        $dto = $this->dto;

        $this->builder
            ->where(self::ALIAS . '.id>0');


        $this->initialise_where_mprocess();

        $this->initialise_where_process();

        $this->initialise_where_owner();

        $this->initialise_where_new();

        $this->initialise_where_is_updatable();

        $this->initialise_where_is_for_subscription();

        $this->initialise_where_enable();

        $this->initialise_where_state();

        $this->initialise_where_state_in_progress();

        $this->initialise_where_states_show();

        $this->initialise_where_go_to_revise();

        $this->initialise_where_search();

        if (count($this->params) > 0) {
            $this->builder->setParameters($this->params);
        }
    }


    private function initialise_where_new()
    {
        if ($this->dto->getIsNew() == BackpackDto::TRUE) {
            $to = date('Y-m-d', strtotime((new DateTime())->format('Y-m-d') . ' +1 day'));
            $from = date('Y-m-d', strtotime((new DateTime())->format('Y-m-d') . ' -8 day'));

            $this->builder->andWhere(
                self::ALIAS . '.updatedAt BETWEEN  :from AND :to'
            );

            $this->addParams('from', $from);
            $this->addParams('to', $to);
        }
    }

    private function initialise_where_is_updatable()
    {
        $contributor = ($this->dto->getIsContributor() == BackpackDto::TRUE);
        $validator = ($this->dto->getIsValidator() == BackpackDto::TRUE);
        $validatorForCategory = ($this->dto->getIsValidatorForCategory() == BackpackDto::TRUE);

        if ($contributor || $validator || $validatorForCategory) {
            $u = $this->dto->getUserDto();
            if (empty($u)) {
                $u = $this->dto->getOwnerDto();
            }
            if (!empty($u) && !empty($u->getId())) {

                $rqtMPContributor = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.mProcess', MProcessRepository::ALIAS . '1')
                    ->join(MProcessRepository::ALIAS . '1.contributors', UserRepository::ALIAS_MP_C . '1')
                    ->where(UserRepository::ALIAS_MP_C . '1.id= :idUser');
                $rqtPContributor = $this->createQueryBuilder(self::ALIAS . '2')
                    ->select(self::ALIAS . '2.id')
                    ->join(self::ALIAS . '2.process', ProcessRepository::ALIAS . '2')
                    ->join(ProcessRepository::ALIAS . '2.contributors', UserRepository::ALIAS_MP_C . '2')
                    ->where(UserRepository::ALIAS_MP_C . '2.id= :idUser');

                $rqtMPValidatorMS = $this->createQueryBuilder(self::ALIAS . '3')
                    ->select(self::ALIAS . '3.id')
                    ->join(self::ALIAS . '3.mProcess', MProcessRepository::ALIAS . '3')
                    ->join(MProcessRepository::ALIAS . '3.poleValidators', UserRepository::ALIAS_MP_PV . '3')
                    ->where(UserRepository::ALIAS_MP_PV . '3.id= :idUser');
                $rqtMPValidatorMSByCategory = $this->createQueryBuilder(self::ALIAS . '6')
                    ->select(self::ALIAS . '6.id')
                    ->join(self::ALIAS . '6.category', CategoryRepository::ALIAS . '6')
                    ->join(self::ALIAS . '6.mProcess', MProcessRepository::ALIAS . '6')
                    ->join(MProcessRepository::ALIAS . '6.poleValidators', UserRepository::ALIAS_MP_PV . '6')
                    ->where(UserRepository::ALIAS_MP_PV . '6.id= :idUser')
                    ->andWhere(categoryRepository::ALIAS . '6.isValidatedByADD=false');
                $rqtMPValidatorADD = $this->createQueryBuilder(self::ALIAS . '5')
                    ->select(self::ALIAS . '5.id')
                    ->join(self::ALIAS . '5.mProcess', MProcessRepository::ALIAS . '5')
                    ->join(MProcessRepository::ALIAS . '5.dirValidators', UserRepository::ALIAS_MP_DV . '5')
                    ->where(UserRepository::ALIAS_MP_DV . '5.id= :idUser');
                $rqtMPValidatorADDByCategory = $this->createQueryBuilder(self::ALIAS . '7')
                    ->select(self::ALIAS . '7.id')
                    ->join(self::ALIAS . '7.category', CategoryRepository::ALIAS . '7')
                    ->join(self::ALIAS . '7.mProcess', MProcessRepository::ALIAS . '7')
                    ->join(MProcessRepository::ALIAS . '7.dirValidators', UserRepository::ALIAS_MP_DV . '7')
                    ->where(UserRepository::ALIAS_MP_DV . '7.id= :idUser')
                    ->andWhere(categoryRepository::ALIAS . '7.isValidatedByADD=true');
                $rqtPValidator = $this->createQueryBuilder(self::ALIAS . '4')
                    ->select(self::ALIAS . '4.id')
                    ->join(self::ALIAS . '4.process', ProcessRepository::ALIAS . '4')
                    ->join(ProcessRepository::ALIAS . '4.validators', UserRepository::ALIAS_P_V . '4')
                    ->where(UserRepository::ALIAS_P_V . '4.id= :idUser');

                $this->addParams('idUser', $u->getId());

                if ($contributor && !$validator && !$validatorForCategory) {

                    $this->builder
                        ->andWhere(
                            '(( ' . self::ALIAS . '.process is null AND ' .
                                self::ALIAS . '.id IN (' . $rqtMPContributor->getDQL() . ')) OR (' .
                                self::ALIAS . '.id IN (' . $rqtPContributor->getDQL() . ')))'
                        );
                } elseif ($contributor && $validator && !$validatorForCategory) {

                    $this->builder
                        ->andWhere(
                            '(( ' . self::ALIAS . '.process is null AND (' .
                                self::ALIAS . '.id IN (' . $rqtMPContributor->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorADD->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorMS->getDQL() . ') ' .
                                ')) OR (' .
                                self::ALIAS . '.id IN (' . $rqtPContributor->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtPValidator->getDQL() . ')' .
                                '))'
                        );
                } elseif ($contributor && !$validator && $validatorForCategory) {

                    $this->builder
                        ->andWhere(
                            '(( ' . self::ALIAS . '.process is null AND (' .
                                self::ALIAS . '.id IN (' . $rqtMPContributor->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorADDByCategory->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorMSByCategory->getDQL() . ') ' .
                                ')) OR (' .
                                self::ALIAS . '.id IN (' . $rqtPContributor->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtPValidator->getDQL() . ')' .
                                '))'
                        );                        
                } elseif (!$contributor && !$validator && $validatorForCategory) {
                    $this->builder
                        ->andWhere(
                            '(( ' . self::ALIAS . '.process is null AND (' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorADDByCategory->getDQL() . ') OR ' .
                                self::ALIAS . '.id IN (' . $rqtMPValidatorMSByCategory->getDQL() . ') ' .
                                ')) OR (' .
                                self::ALIAS . '.id IN (' . $rqtPValidator->getDQL() . ')' .
                                '))'
                        );
                }
            }
        }
    }

    private function initialise_where_is_for_subscription()
    {
        if ($this->dto->getIsForSubscription() == BackpackDto::TRUE) {

            $u = $this->dto->getUserDto();
            if (empty($u)) {
                $u = $this->dto->getOwnerDto();
            }
            if (!empty($u) && !empty($u->getId())) {

                $qWC = $this->createQueryBuilder(self::ALIAS . '1')
                    ->select(self::ALIAS . '1.id')
                    ->join(self::ALIAS . '1.mProcess', MProcessRepository::ALIAS . '1')
                    ->join(MProcessRepository::ALIAS . '1.subscriptions', SubscriptionRepository::ALIAS . '1')
                    ->join(SubscriptionRepository::ALIAS . '1.user', UserRepository::ALIAS_MP_C . '1')
                    ->where(UserRepository::ALIAS_MP_C . '1.id= :idUser')
                    ->andWhere(SubscriptionRepository::ALIAS . '1.isEnable=true');

                $qWC2 = $this->createQueryBuilder(self::ALIAS . '2')
                    ->select(self::ALIAS . '2.id')
                    ->join(self::ALIAS . '2.process', ProcessRepository::ALIAS . '2')
                    ->join(ProcessRepository::ALIAS . '2.subscriptions', SubscriptionRepository::ALIAS . '2')
                    ->join(SubscriptionRepository::ALIAS . '2.user', UserRepository::ALIAS_MP_C . '2')
                    ->where(UserRepository::ALIAS_MP_C . '2.id= :idUser')
                    ->andWhere(SubscriptionRepository::ALIAS . '2.isEnable=true');

                $this->addParams('idUser', $u->getId());

                $this->builder
                    ->andWhere(
                        '(' .
                            self::ALIAS . '.id IN (' . $qWC->getDQL() . ') OR ' .
                            self::ALIAS . '.id IN (' . $qWC2->getDQL() . '))'
                    );
            }
        }
    }

    private function initialise_where_state()
    {
        if (!empty($this->dto->getStateCurrent())) {
            $this->builder->andwhere(self::ALIAS . '.stateCurrent = :state');
            $this->addParams('state', $this->dto->getStateCurrent());
        }
    }

    private function initialise_where_state_in_progress()
    {
        if (!empty($this->dto->getIsInProgress())) {
            $states = implode('\',\'', $this->dto->getStatesInProgress());
            $this->builder->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');
        }
    }


    private function initialise_where_states_show()
    {
        if (!empty($this->dto->getIsShow())) {
            $states = implode('\',\'', $this->dto->getStatesShow());
            $this->builder->andwhere(self::ALIAS . '.stateCurrent in (\'' . $states . '\')');
        }
    }

    private function initialise_where_go_to_revise()
    {
        if (!empty($this->dto->getIsGoToRevise())) {
            $this->builder->andWhere(' CURRENT_DATE() >= DATE_ADD('. self::ALIAS. '.stateAt,'. CategoryRepository::ALIAS.'.timeBeforeRevision,\'month\')');
        }
    }

    private function initialise_where_enable()
    {

        if (!empty($this->dto->getVisible())) {
            $this->builder->andwhere(MProcessRepository::ALIAS . '.isEnable= true');
            $this->builder->andWhere('(' . ProcessRepository::ALIAS . '.isEnable= true or ' . ProcessRepository::ALIAS . '.isEnable is null)');
        } else if (!empty($this->dto->getHide())) {
            $this->builder->andWhere(
                MProcessRepository::ALIAS . '.isEnable= false OR ' .
                    ProcessRepository::ALIAS . '.isEnable= false '
            );
        } else {
            $e = $this->dto->getMProcessDto();
            if (!empty($e)) {
                if ($e->getIsEnable() == MProcessDto::TRUE) {
                    $this->builder->andwhere(MProcessRepository::ALIAS . '.isEnable= true');
                } elseif ($e == MProcessDto::FALSE) {
                    $this->builder->andwhere(MProcessRepository::ALIAS . '.isEnable= false');
                }
            }

            $e = $this->dto->getProcessDto();
            if (!empty($e)) {
                if ($e->getIsEnable() == ProcessDto::TRUE) {
                    $this->builder->andWhere(ProcessRepository::ALIAS . '.isEnable= true');
                } elseif ($e->getIsEnable() == ProcessDto::FALSE) {
                    $this->builder->andWhere(ProcessRepository::ALIAS . '.isEnable= false');
                }
            }
        }
    }

    private function initialise_where_mprocess()
    {
        $r = $this->dto->getMProcessDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(MProcessRepository::ALIAS . '.id = :mprocessid');
            $this->addParams('mprocessid', $r->getId());
        }
    }

    private function initialise_where_process()
    {
        $r = $this->dto->getProcessDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(ProcessRepository::ALIAS . '.id = :processid');
            $this->addParams('processid', $r->getId());
        }
    }

    private function initialise_where_owner()
    {
        $r = $this->dto->getOwnerDto();
        if (!empty($r) && !empty($r->getId())) {
            $this->builder->andwhere(self::ALIAS . '.owner = :ownerid');
            $this->addParams('ownerid', $r->getId());
        }
    }

    private function initialise_where_search()
    {
        $dto = $this->dto;
        $builder = $this->builder;
        if (!empty($dto->getRef())) {
            $builder->andwhere(self::ALIAS . '.ref like :bpref');
            $this->addParams('bpref', $dto->getRef());
        } elseif  (!empty($dto->getSearchDate())) {
                $builder
                    ->andWhere(
                        self::ALIAS . '.createdAt like :search ' .
                        ' OR ' . self::ALIAS . '.updatedAt like :search' .
                        ' OR ' . self::ALIAS . '.stateAt like :search' 
                    );
                $this->addParams('search', $dto->getSearchDate());
        }
        elseif (!empty($dto->getSearch())) {
            $builder
                ->andwhere(
                    self::ALIAS . '.content like :search' .
                        ' OR ' . self::ALIAS . '.dir1 like :search' .
                        ' OR ' . self::ALIAS . '.dir2 like :search' .
                        ' OR ' . self::ALIAS . '.dir3 like :search' .
                        ' OR ' . self::ALIAS . '.dir4 like :search' .
                        ' OR ' . self::ALIAS . '.dir5 like :search' .
                        ' OR ' . self::ALIAS . '.name like :search' .
                        ' OR ' . self::ALIAS . '.stateContent like :search' .
                        ' OR ' . BackpackLinkRepository::ALIAS . '.title like :search' .
                        ' OR ' . BackpackLinkRepository::ALIAS . '.link like :search' .
                        ' OR ' . BackpackLinkRepository::ALIAS . '.content like :search' .
                        ' OR ' . BackpackFileRepository::ALIAS . '.title like :search' .
                        ' OR ' . BackpackFileRepository::ALIAS . '.fileName like :search' .
                        ' OR ' . BackpackFileRepository::ALIAS . '.content like :search'
                );

            $this->addParams('search', '%' . $dto->getSearch() . '%');
        }
    }

    private function initialise_orderBy()
    {
        $this->builder
            ->orderBy(MProcessRepository::ALIAS . '.showOrder', 'ASC')
            ->addOrderBy(MProcessRepository::ALIAS . '.ref', 'ASC')
            ->addOrderBy(MProcessRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(processRepository::ALIAS . '.ref', 'ASC')
            ->addOrderBy(processRepository::ALIAS . '.name', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir1', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir2', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir3', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir4', 'ASC')
            ->addOrderBy(self::ALIAS . '.dir5', 'ASC')
            ->addOrderBy(self::ALIAS . '.ref', 'ASC')
            ->addOrderBy(self::ALIAS . '.name', 'ASC');
    }
}
