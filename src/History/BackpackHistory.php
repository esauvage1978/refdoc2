<?php


namespace App\History;


use App\Entity\Backpack;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

class BackpackHistory extends HistoryAbstract
{
    public function __construct(
        HistoryManager $manager,
        CurrentUser $currentUser
    )
    {
        parent::__construct($manager, $currentUser);
    }

    public function compare(Backpack $backpackOld, Backpack $backpackNew)
    {
        $this->history->setBackpack($backpackNew);
        $diffPresent = false;

        $this->compareField('Type de porte-document', $backpackOld->getCategory()->getName(), $backpackNew->getCategory()->getName()) && $diffPresent = true;
        $this->compareFieldOneToOne('Macro-processus','FullName', $backpackOld->getMProcess(), $backpackNew->getMProcess()) && $diffPresent = true;
        $this->compareFieldOneToOne('Processus', 'FullName', $backpackOld->getProcess(), $backpackNew->getProcess()) && $diffPresent = true;
        $this->compareField('Nom', $backpackOld->getName(), $backpackNew->getName()) && $diffPresent = true;
        $this->compareField('Description', $backpackOld->getContent(), $backpackNew->getContent()) && $diffPresent = true;
        $this->compareField('Niveau 1', $backpackOld->getDir1(), $backpackNew->getDir1()) && $diffPresent = true;
        $this->compareField('Niveau 2', $backpackOld->getDir2(), $backpackNew->getDir2()) && $diffPresent = true;
        $this->compareField('Niveau 3', $backpackOld->getDir3(), $backpackNew->getDir3()) && $diffPresent = true;
        $this->compareField('Niveau 4', $backpackOld->getDir4(), $backpackNew->getDir4()) && $diffPresent = true;
        $this->compareField('Niveau 5', $backpackOld->getDir5(), $backpackNew->getDir5()) && $diffPresent = true;


        
        if ($diffPresent) {
            $this->save();
        }
    }
}