<?php

namespace App\Workflow;

use App\Entity\Backpack;
use App\Repository\BackpackRepository;

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

    /**
     * @var BackpackRepository
     */
    private $backpackRepository;

    public function __construct(Backpack $backpack, BackpackRepository $backpackRepository)
    {
        $this->backpack = $backpack;
        $this->backpackCheckMessage = new  BackpackCheckMessage();
        $this->backpackRepository= $backpackRepository;
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
        } else {
            $this->backpackCheckMessage->addMessageSuccess('Description ou fichiers');
        }
    }

    public function checkRef()
    {
        if (empty($this->backpack->getRef())) {
            $this->backpackCheckMessage->addMessageError('Référence non renseignée');
        } else {
            $this->backpackCheckMessage->addMessageSuccess('Référence');
        }
    }

    public function checkRefUnique()
    {
        if (empty($this->backpack->getRef())) {
            return;
        }
        $nbr=$this->backpackRepository->findCountForRefPatternCheck(
            $this->backpack->getRef(),
            $this->backpack->getId()
        );
        if ($nbr>0) {
            $this->backpackCheckMessage->addMessageError('Référence non unique');
        } else {
            $this->backpackCheckMessage->addMessageSuccess('Référence unique');
        }
    }

}
