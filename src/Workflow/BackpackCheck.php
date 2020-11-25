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

    public function checkContent()
    {
        if (empty($this->backpack->getContent())) {
            $this->backpackCheckMessage->addMessageError('Vous devez saisir une description');
        } else {
            $this->backpackCheckMessage->addMessageSuccess('Description');
        }
    }

}
