<?php


namespace App\Workflow\Transaction;


class TransitionGoToValidate  extends TransitionAbstract
{

    public function getExplains(): array
    {
        return
            [
                'En cliquant sur le bouton ci-dessous, Vous envoyez ce porte-document à la validation hiérarchique.'
            ];
    }

    public function check()
    {
        $this->checkAll();
    }

}