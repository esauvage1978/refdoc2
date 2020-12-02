<?php


namespace App\Workflow\Transaction;


use App\Entity\Backpack;
use App\Repository\BackpackRepository;
use App\Workflow\BackpackCheck;

class TransitionAbstract implements Transition
{
    /**
     * @var Backpack
     */
    protected $backpack;



    /**
     * @var BackpackCheck
     */
    protected $backpackCheck;

    public function __construct(Backpack $item, BackpackRepository $backpackRepository)
    {
        $this->backpack=$item;
        $this->backpackCheck=new BackpackCheck($item, $backpackRepository);
    }

    public function can(): bool
    {
        $this->check();
        return !$this->backpackCheck->hasMessageError();
    }

    public function check()
    {

    }

    public function getExplains(): array
    {
        return [];
    }

    public function getCheckMessages(): array
    {
        $this->check();
        return $this->backpackCheck->getMessages();
    }

    public function checkAll()
    {
        $this->backpackCheck->checkName();
        $this->backpackCheck->checkContentOrFile();
    }

    public function intialiseBackpackForTransition(bool $automate=false)
    {

    }
}
