<?php


namespace App\Workflow\Transaction;


class TransitionGoPublished  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                '<strong>PUBLIER</strong> ce porte-document. Il sera automatiquement consultable par l\'ensemble du personnel. En l\'absence de référence, elle sera automaiquement générée.'
            ];
    }

    public function check()
    {
        $this->checkAll();
        $this->backpackCheck->checkRef();
        $this->backpackCheck->checkRefUnique();
    }

}