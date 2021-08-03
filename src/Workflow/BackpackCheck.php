<?php

namespace App\Workflow;

use App\Entity\Backpack;
use App\Service\BackpackRefGenerator;
use App\Repository\BackpackRepository;
use App\Service\BackpackRefControllator;
use App\Service\BackpackGetPatternRefTrait;

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
        $brc= new BackpackRefControllator($this->backpackRepository,$this->backpack);

        if ($brc->isUnique($this->backpack->getRef())) {
            $this->backpackCheckMessage->addMessageSuccess('Référence unique');
        } else {
            $this->backpackCheckMessage->addMessageError('Référence non unique');
        }
    }

    public function checkRefCoherent()
    {
        if (empty($this->backpack->getRef())) {
            return;
        }
        $brc = new BackpackRefControllator($this->backpackRepository, $this->backpack);

        if ($brc->isCoherent()) {
            $this->backpackCheckMessage->addMessageSuccess('Référence cohérente');
        } else {
            $this->backpackCheckMessage->addMessageError('La référence n\'est pas cohérente. Elle doit commencer par : ' . $brc->getPattern() );
        }
    }

}
