<?php


namespace App\Workflow\Transaction;


class TransitionGoInReview  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
            'Envoyer <strong>EN REVISION</strong> . Une copie de ce porte document sera conservé pour la consultation par l\'ensemble du personnel. Le porte-document orignial sera placé dans l\'état <strong>à reprendre</strong>.'
            ];
    }

    public function check()
    {
        $this->checkAll();
        $this->backpackCheck->checkRef();
        $this->backpackCheck->checkRefUnique();
        $this->backpackCheck->checkRefCoherent();
    }

}