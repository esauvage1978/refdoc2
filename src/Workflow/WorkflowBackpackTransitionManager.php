<?php


namespace App\Workflow;


use App\Entity\Backpack;
use App\Repository\BackpackRepository;

class WorkflowBackpackTransitionManager
{
    /**
     * @var Backpack
     */
    private $backpack;

    /**
     * @var BackpackRepository
     */
    private $BackpackRepository;


    /**
     * @var string
     */
    private $transition;


    public function __construct(Backpack $backpack, BackpackRepository $backpackRepository, string $transition = '')
    {
        $this->backpack=$backpack;
        $this->transition=$transition;
        $this->backpackRepository= $backpackRepository;
    }

    public function intialiseBackpackForTransition(string $content, bool $automate=false)
    {
        $this->backpack->setStateAt(new \DateTime());
        $this->backpack->setStateContent($content);

        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->backpack, $this->backpackRepository);
        $instance->intialiseBackpackForTransition($automate);
    }

    public function can(): bool
    {
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->backpack, $this->backpackRepository);
        return $instance->can();
    }
}