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

    public const DRAFT = 'draft';
    public const MY_DRAFT_UPDATABLE = 'mydraft_updatable';
    public const DRAFT_UPDATABLE = 'draft_updatable';

    public const ABANDONNED = 'abandonned';
    public const ABANDONNED_UPDATABLE = 'abandonned_updatable';
    public const MY_ABANDONNED_UPDATABLE = 'myabandonned_updatable';

    public const TO_RESUME = 'toResume';
    public const TO_RESUME_UPDATABLE = 'toResume_updatable';
    public const MY_TO_RESUME_UPDATABLE = 'mytoResume_updatable';


private const STATE='state';

    public const TO_VALIDATE = 'to_validate';

    public function getData(string $filter)
    {
        $datas = [
            self::DRAFT => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Les brouillons',
            ],
            self::DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Les brouillons modifiables',
            ],
            self::MY_DRAFT_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_DRAFT,
                self::TITLE => 'Mes brouillons modifiables',
            ],
            self::ABANDONNED => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés',
            ],
            self::ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Les abandonnés modifiables',
            ],
            self::MY_ABANDONNED_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_ABANDONNED,
                self::TITLE => 'Mes abandonnés modifiables',
            ],
            self::TO_RESUME => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Les porte-documents à reprendre',
            ],
            self::TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Les porte-documents à reprendre modifiables',
            ],
            self::MY_TO_RESUME_UPDATABLE => [
                self::STATE =>  WorkflowData::STATE_TO_RESUME,
                self::TITLE => 'Mes porte-documents à reprendre modifiables',
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
