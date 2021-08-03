<?php


namespace App\Workflow\Transaction;


class TransitionGoToValidate  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                '<strong>ENVOYER A LA VALIDATION</strong> hiérarchique ce porte document.'
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