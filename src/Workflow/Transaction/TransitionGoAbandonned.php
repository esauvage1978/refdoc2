<?php


namespace App\Workflow\Transaction;


class TransitionGoAbandonned extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['Vous pouvez abandonner ce porte-document.'];
    }
}