<?php

namespace App\Service;

use App\Dto\BackpackDto;
use App\Dto\UserDto;
use App\Entity\User;
use App\Repository\BackpackDtoRepository;
use App\Widget\WidgetInfoBox;
use App\Workflow\WorkflowData;

class MakeDashboard
{
    /**
     * @var BackpackCounter
     */
    private $counter;


    private const ROUTE = 'route';
    private const ROUTE_OPTIONS = 'route_options';
    private const BG_COLOR = 'bgColor';
    private const FORE_COLOR = 'foreColor';
    private const TITLE = 'title';
    private const ICONE = 'icone';
    private const NBR = 'nbr';


private const STATE='state';


    public function getData(string $filter)
    {
        $datas = [
            BackpackMakerDto::HOME_NEWS => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Les actualités',
            ],
            BackpackMakerDto::HOME_NEWS_SUBSCRIPTION => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Les actualités / Abonnement',
            ],
            BackpackMakerDto::GO_TO_REVISE => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Sera basculé à l\'état à réviser',
            ],
            BackpackMakerDto::DRAFT => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Tous les brouillons',
            ],
            BackpackMakerDto::DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Les brouillons',
            ],
            BackpackMakerDto::MY_DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Mes brouillons',
            ],
            BackpackMakerDto::ABANDONNED => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés',
            ],
            BackpackMakerDto::ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés',
            ],
            BackpackMakerDto::MY_ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Mes abandonnés',
            ],
            BackpackMakerDto::ARCHIVED => [
                self::STATE =>  WorkflowData::STATE_ARCHIVED,
                self::TITLE => 'Les archives',
            ],
            BackpackMakerDto::ARCHIVED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ARCHIVED,
                self::TITLE => 'Les archives',
            ],
            BackpackMakerDto::MY_ARCHIVED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ARCHIVED,
                self::TITLE => 'Mes archives',
            ],           
            BackpackMakerDto::TO_RESUME => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Tous ceux à reprendre',
            ],
            BackpackMakerDto::TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'A reprendre',
            ],
            BackpackMakerDto::MY_TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'A reprendre (vos porte-documents)',
            ],
            BackpackMakerDto::TO_VALIDATE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'Tous ceux à valider',
            ],
            BackpackMakerDto::TO_VALIDATE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'A valider',
            ],
            BackpackMakerDto::MY_TO_VALIDATE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'A valider  (vos porte-documents)',
            ],
            BackpackMakerDto::TO_CONTROL => [
                self::STATE =>  WorkflowData::STATE_TO_CONTROL,
                self::TITLE => 'A contrôler',
            ],
            BackpackMakerDto::TO_CHECK => [
                self::STATE =>  WorkflowData::STATE_TO_CHECK,
                self::TITLE => 'A vérifier',
            ],
            BackpackMakerDto::PUBLISHED => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Les publiés',
            ],
            BackpackMakerDto::PUBLISHED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Les publiés modifiables',
            ],
            BackpackMakerDto::MY_PUBLISHED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_PUBLISHED,
                self::TITLE => 'Mes publiés modifiables',
            ],
            BackpackMakerDto::TO_REVISE => [
                self::STATE =>  WorkflowData::STATE_TO_REVISE,
                self::TITLE => 'A réviser',
            ],
            BackpackMakerDto::TO_REVISE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_REVISE,
                self::TITLE => 'A réviser',
            ],
            BackpackMakerDto::MY_TO_REVISE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_REVISE,
                self::TITLE => 'A réviser (Mes porte-documents)',
            ],
            BackpackMakerDto::IN_REVIEW => [
                self::STATE =>  WorkflowData::STATE_IN_REVIEW,
                self::TITLE => 'En révision',
            ],
            BackpackMakerDto::IN_REVIEW_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_IN_REVIEW,
                self::TITLE => 'En révision',
            ],
            BackpackMakerDto::MY_IN_REVIEW_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_IN_REVIEW,
                self::TITLE => 'En révision (Mes porte-documents)',
            ],                            
        ];


        return $this->getArray($datas[$filter],$filter);
    }

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user
    ) {
        $this->counter = new BackpackCounter($backpackDtoRepository, $user);
    }

    private function getArray($datas,$filter)
    {
        $ib = new WidgetInfoBox();

        return $ib
            ->setRoute('backpacks_' . $filter)
            ->setRouteOptions(key_exists(self::ROUTE_OPTIONS,$datas)? $datas[self::ROUTE_OPTIONS]:null)
            ->setBgColor(WorkflowData::getBGColorOfState($datas[self::STATE]))
            ->setForeColor(WorkflowData::getForeColorOfState($datas[self::STATE]))
            ->setIcone(WorkflowData::getIconOfState($datas[self::STATE]))
            ->setTitle($datas[self::TITLE])
            ->setData($this->counter->get($filter))
            ->createArray();
    }


}
