<?php

namespace App\Workflow\Transaction;

use App\Entity\Backpack;

interface Transition
{
    public function __construct(Backpack $item);
    public function can();
    public function getExplains(): array;
    public function getCheckMessages(): array;
    public function check();
    public function intialiseBackpackForTransition(bool $automate=false);
}
