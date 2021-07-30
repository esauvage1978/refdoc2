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

    public function setHistoryRelation(EntityInterface $entity)
    {
        $historyEntity = $this->history->getHistoryRelationEntity()->setBackpack($entity);
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
                ->setDataOld($itemOld->getCategory())
                ->setDataNew($itemNew->getCategory())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Macro-processus")
                ->setDataOld($itemOld->getMProcess())
                ->setDataNew($itemNew->getMProcess())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("FullName"),
            (new HistoryData())
                ->setTitle("Processus")
                ->setDataOld($itemOld->getProcess())
                ->setDataNew($itemNew->getProcess())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_ONE)
                ->setField("FullName"),
            (new HistoryData())
                ->setTitle("Nom")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Name"),
            (new HistoryData())
                ->setTitle("Référence")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Ref"),
            (new HistoryData())
                ->setTitle("Description")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Content"),
            (new HistoryData())
                ->setTitle("Niveau 1")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Dir1"),
            (new HistoryData())
                ->setTitle("Niveau 2")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Dir2"),
            (new HistoryData())
                ->setTitle("Niveau 3")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Dir3"),
            (new HistoryData())
                ->setTitle("Niveau 4")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Dir4"),
            (new HistoryData())
                ->setTitle("Niveau 5")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setField("Dir5"),
            (new HistoryData())
                ->setTitle("Liens")
                ->setDataOld($itemOld->getBackpackLinks())
                ->setDataNew($itemNew->getBackpackLinks())
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("Title"),
        ];

        $this->history->compare($compare);
    }

    public function compareLinks($itemOld, $itemNew)
    {

        $compare = [
            (new HistoryData())
                ->setTitle("Nom du lien")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("Title"),
            (new HistoryData())
                ->setTitle("Adresse du lien")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("Link"),
            (new HistoryData())
                ->setTitle("Description du lien")
                ->setDataOld($itemOld)
                ->setDataNew($itemNew)
                ->setTypeOfCompare(HistoryTypeOfCompare::RELATION_ONE_TO_MANY)
                ->setField("Content"),
        ];

        $this->history->compare($compare);
    }
}
