<?php


namespace App\Workflow\Transaction;


class TransitionGoToResume extends TransitionAbstract
{
    public function getExplains(): array
    {
        return ['<strong>REJETER</strong> ce porte-document en l\'envoyant à l\'état "à reprendre".'];
    }

    public function check()
    {
        $this->checkAll();
        $this->backpackCheck->checkRef();
        $this->backpackCheck->checkRefUnique();
        $this->backpackCheck->checkRefCoherent();
    }
}