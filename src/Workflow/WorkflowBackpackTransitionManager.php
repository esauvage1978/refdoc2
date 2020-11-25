<?php


namespace App\Workflow;


use App\Entity\Backpack;

class WorkflowBackpackTransitionManager
{
    /**
     * @var Backpack
     */
    private $backpack;

    /**
     * @var string
     */
    private $transition;


    public function __construct(Backpack $backpack,string $transition='')
    {
        $this->backpack=$backpack;
        $this->transition=$transition;
    }

    public function intialiseBackpackForTransition(string $content, bool $automate=false)
    {
        $this->backpack->setStateAt(new \DateTime());
        $this->backpack->setStateContent($content);
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->backpack);
        $instance->intialiseBackpackForTransition($automate);
    }

    public function can(): bool
    {
        $object = __NAMESPACE__ . '\Transaction\Transition' . ucfirst( $this->transition);
        $instance=new $object($this->backpack);
        return $instance->can();
    }
}