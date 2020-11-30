<?php

namespace App\Workflow;

use App\Entity\Backpack;

class BackpackCheck
{
    /**
     * var Backpack
     */
    private $backpack;

    /**
     * @var BackpackCheckMessage
     */
    private $backpackCheckMessage;

    public function __construct(Backpack $backpack)
    {
        $this->backpack = $backpack;
        $this->backpackCheckMessage = new  BackpackCheckMessage();
    }

    public function hasMessageError(): bool
    {
        return $this->backpackCheckMessage->hasMessageError();
    }

    public function getMessages(): array
    {
        return $this->backpackCheckMessage->getMessages();
    }

    public function checkName()
    {
        if (empty($this->backpack->getName())) {
            $this->backpackCheckMessage->addMessageError('Nom non renseigné');
        } else {
            $this->backpackCheckMessage->addMessageSuccess('Nom');
        }
    }

    public function checkContentOrFile()
    {
        $nbr = $this->backpack->getBackpackFiles()->count() + $this->backpack->getBackpackLinks()->count();
        if (($this->backpack->getContent()==='<br>' or $this->backpack->getContent()===null) && $nbr== 0) {
            $this->backpackCheckMessage->addMessageError('Vous devez saisir une description ou ajouter des fichiers ou des liens');
        } 
    }

}
