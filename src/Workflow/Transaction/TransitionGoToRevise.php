<?php


namespace App\Workflow\Transaction;


class TransitionGoToRevise  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
            'Forcer ce porte-document <strong>A ETRE REVISER</strong> . Il sera toujours consultable par l\'ensemble du personnel. Cette étape est automatique lorsque le porte-document doit être révisé.'
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