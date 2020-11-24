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
    public const TO_VALIDATE = 'to_validate';

    public function getData(string $data)
    {
        $datas = [
            self::DRAFT => [
                self::ROUTE => 'backpacks_' . self::DRAFT,
                self::ROUTE_OPTIONS => null,
                self::BG_COLOR => WorkflowData::getBGColorOfState(WorkflowData::STATE_DRAFT),
                self::FORE_COLOR => WorkflowData::getForeColorOfState(WorkflowData::STATE_DRAFT),
                self::ICONE => WorkflowData::getIconOfState(WorkflowData::STATE_DRAFT),
                self::TITLE => 'Les brouillons',
                self::NBR => $this->counter->get(BackpackMakerDto::DRAFT),
            ],
            self::DRAFT_UPDATABLE => [
                self::ROUTE => 'backpacks_' . self::DRAFT_UPDATABLE,
                self::ROUTE_OPTIONS => null,
                self::BG_COLOR => WorkflowData::getBGColorOfState(WorkflowData::STATE_DRAFT),
                self::FORE_COLOR => WorkflowData::getForeColorOfState(WorkflowData::STATE_DRAFT),
                self::ICONE => WorkflowData::getIconOfState(WorkflowData::STATE_DRAFT),
                self::TITLE => 'Les brouillons modifiables',
                self::NBR => $this->counter->get(BackpackMakerDto::DRAFT_UPDATABLE),
            ],
            self::MY_DRAFT_UPDATABLE => [
                self::ROUTE => 'backpacks_' . self::MY_DRAFT_UPDATABLE,
                self::ROUTE_OPTIONS => null,
                self::BG_COLOR => WorkflowData::getBGColorOfState(WorkflowData::STATE_DRAFT),
                self::FORE_COLOR => WorkflowData::getForeColorOfState(WorkflowData::STATE_DRAFT),
                self::ICONE => WorkflowData::getIconOfState(WorkflowData::STATE_DRAFT),
                self::TITLE => 'Mes brouillons modifiables',
                self::NBR => $this->counter->get(BackpackMakerDto::MY_DRAFT_UPDATABLE),
            ],
         
        ];


        return $this->getArray($datas[$data]);
    }

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user
    ) {
        $this->counter = new BackpackCounter($backpackDtoRepository, $user);
    }

    private function getArray($datas)
    {
        $ib = new WidgetInfoBox();

        return $ib
            ->setRoute($datas[self::ROUTE])
            ->setRouteOptions($datas[self::ROUTE_OPTIONS])
            ->setBgColor($datas[self::BG_COLOR])
            ->setForeColor($datas[self::FORE_COLOR])
            ->setIcone($datas[self::ICONE])
            ->setTitle($datas[self::TITLE])
            ->setData($datas[self::NBR])
            ->createArray();
    }


}
