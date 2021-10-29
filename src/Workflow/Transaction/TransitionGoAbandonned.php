<?php


namespace App\Workflow\Transaction;


class TransitionGoAbandonned extends TransitionAbstract
{
    public function getExplains(): array
    {
        return [
            '<strong>ABANDONNER</strong> ce porte-document.',
            'L\'abandon d\'un porte-document est la première étape avant la suppression.' 
        ];
    }
}

