<?php


namespace App\History;


use App\Entity\Backpack;
use App\Entity\EntityInterface;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

class BackpackHistory implements HistoryEntityInterface
{

    protected $history;

    public function __construct(
        History $history
    ) {
        $this->history = $history;
    }

    public function setHistoryRelation(EntityInterface $entity,string $domaine)
    {
        $historyEntity = $this->history->getHistoryRelationEntity()
        ->setBackpack($entity)
        ->setDomaine($domaine);
        $this->history->setHistoryRelationEntity($historyEntity);
    }

    public function compare(EntityInterface $itemOld, EntityInterface $itemNew)
    {
        /**
         * @var Backpack
         */
        $itemOld = $itemOld;
        /**
         * @var Backpack
         */
        $itemNew = $itemNew;

        $compare = [
            (new HistoryData())
                ->setTitle("Type de porte-document")
                ->setOldData($itemOld->getCategory())
                ->setNewData($itemNew->getCategory())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Macro-processus")
                ->setOldData($itemOld->getMProcess())
                ->setNewData($itemNew->getMProcess())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("FullName"),
            (new HistoryData())
                ->setTitle("Processus")
                ->setOldData($itemOld->getProcess())
                ->setNewData($itemNew->getProcess())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("FullName"),
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Référence")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Ref"),
            (new HistoryData())
                ->setTitle("Description")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Content"),
            (new HistoryData())
                ->setTitle("Niveau 1")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Dir1"),
            (new HistoryData())
                ->setTitle("Niveau 2")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Dir2"),
            (new HistoryData())
                ->setTitle("Niveau 3")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Dir3"),
            (new HistoryData())
                ->setTitle("Niveau 4")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Dir4"),
            (new HistoryData())
                ->setTitle("Niveau 5")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Dir5"),

        ];

        $this->history->compare($compare);
    }

    public function compareLink($itemOld, $itemNew)
    {

        $compare = [
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Title"),
            (new HistoryData())
                ->setTitle("URL")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Link"),
            (new HistoryData())
                ->setTitle("Description du lien")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Content"),
        ];

        $this->history->compare($compare);
    }

    public function compareFile($itemOld, $itemNew)
    {

        $compare = [
            (new HistoryData())
                ->setTitle("Nom")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Title"),
            (new HistoryData())
                ->setTitle("nom du fichier")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("fileName"),
            (new HistoryData())
                ->setTitle("Description du lien")
                ->setOldData($itemOld)
                ->setNewData($itemNew)
                ->setField("Content"),
        ];

        $this->history->compare($compare);
    }
}
