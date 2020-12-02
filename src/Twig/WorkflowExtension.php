<?php


namespace App\Twig;


use Twig\TwigFilter;
use App\Entity\Action;
use App\Entity\Backpack;
use App\Workflow\WorkflowData;
use Twig\Extension\AbstractExtension;
use App\Repository\BackpackRepository;

class WorkflowExtension extends AbstractExtension
{
    /**
     * @var BackpackRepository
     */
    private $BackpackRepository;

    public function __construct(BackpackRepository $backpackRepository)
    {
        $this->backpackRepository = $backpackRepository;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('workflowGetNameOfState', [$this, 'workflowGetNameOfState']),
            new TwigFilter('workflowGetShortNameOfState', [$this, 'workflowGetShortNameOfState']),
            new TwigFilter('workflowGetForeColorOfState', [$this, 'workflowGetForeColorOfState']),
            new TwigFilter('workflowGetBGColorOfState', [$this, 'workflowGetBGColorOfState']),
            new TwigFilter('workflowGetModalDataForTransition', [$this, 'workflowGetModalDataForTransition']),
            new TwigFilter('workflowGetTransitionsForState', [$this, 'workflowGetTransitionsForState']),
            new TwigFilter('workflowGetExplains', [$this, 'workflowGetExplains']),
            new TwigFilter('workflowGetCheckMessages', [$this, 'workflowGetCheckMessages']),
            new TwigFilter('workflowGetIconOfState', [$this, 'workflowGetIconOfState']),
        ];
    }


    public function workflowGetNameOfState(string $state)
    {
        return WorkflowData::getNameOfState($state);
    }

    public function workflowGetShortNameOfState(string $state)
    {
        return WorkflowData::getShortNameOfState($state);
    }
    public function workflowGetBGColorOfState(string $state)
    {
        return WorkflowData::getBGColorOfState($state);
    }
    public function workflowGetForeColorOfState(string $state)
    {
        return WorkflowData::getForeColorOfState($state);
    }

    public function workflowGetIconOfState(string $state)
    {
        return WorkflowData::getIconOfState($state);
    }

    public function workflowGetTransitionsForState(string $workflow, string $state)
    {
        return WorkflowData::getTransitionsForState($workflow, $state);
    }

    public function workflowGetModalDataForTransition(string $transition)
    {
        return WorkflowData::getModalDataForTransition($transition);
    }

    public function workflowGetExplains(Backpack $backpack, string $transition)
    {
        $object =  'App\Workflow\Transaction\Transition' . ucfirst($transition);
        $instance = new $object($backpack, $this->backpackRepository);
        return $instance->getExplains();
    }


    public function workflowGetCheckMessages(Backpack $backpack, string $transition)
    {
        $object =  'App\Workflow\Transaction\Transition' . ucfirst($transition);
        $instance = new $object($backpack, $this->backpackRepository);
        return $instance->getCheckMessages();
    }

}
