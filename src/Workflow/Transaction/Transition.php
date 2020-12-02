<?php

namespace App\Workflow\Transaction;

use App\Entity\Backpack;
use App\Repository\BackpackRepository;

interface Transition
{
    public function __construct(Backpack $item, BackpackRepository $backpackRepository);
    public function can();
    public function getExplains(): array;
    public function getCheckMessages(): array;
    public function check();
    public function intialiseBackpackForTransition(bool $automate=false);
}
