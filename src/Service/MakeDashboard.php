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
            BackpackMakerDto::DRAFT => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Les brouillons',
            ],
            BackpackMakerDto::DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Les brouillons modifiables',
            ],
            BackpackMakerDto::MY_DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Mes brouillons modifiables',
            ],
            BackpackMakerDto::ABANDONNED => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés',
            ],
            BackpackMakerDto::ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés modifiables',
            ],
            BackpackMakerDto::MY_ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Mes abandonnés modifiables',
            ],
            BackpackMakerDto::TO_RESUME => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Les porte-documents à reprendre',
            ],
            BackpackMakerDto::TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'A reprendre et modifiables',
            ],
            BackpackMakerDto::MY_TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Mes porte-documents à reprendre',
            ],
            BackpackMakerDto::TO_VALIDATE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'Les porte-documents à valider',
            ],
            BackpackMakerDto::TO_VALIDATE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'A valider et modifiables',
            ],
            BackpackMakerDto::MY_TO_VALIDATE_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_VALIDATE,
                self::TITLE => 'Mes porte-documents à valider',
            ],
            BackpackMakerDto::TO_CONTROL => [
                self::STATE =>  WorkflowData::STATE_TO_CONTROL,
                self::TITLE => 'Les porte-documents à contrôler',
            ],
            BackpackMakerDto::TO_CHECK => [
                self::STATE =>  WorkflowData::STATE_TO_CHECK,
                self::TITLE => 'Les porte-documents à vérifier',
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
