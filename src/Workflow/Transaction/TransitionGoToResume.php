<?php


namespace App\Workflow\Transaction;


class TransitionGoToResume extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['Vous pouvez mettre ce porte-document dans l\'état <strong>A reprendre</strong>.'];
    }
}