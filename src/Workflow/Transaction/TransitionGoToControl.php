<?php


namespace App\Workflow\Transaction;


class TransitionGoToControl  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                '<strong>APPROUVER</strong> ce porte-document en l\'envoyant à la validation du service contrôle.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}