<?php


namespace App\Workflow\Transaction;


class TransitionGoToCheck  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                '<strong>APPROUVER</strong> ce porte-document en l\'envoyant à la vérification du service documentation.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}