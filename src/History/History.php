<?php


namespace App\History;


use App\Entity\Backpack;
use App\Entity\EntityInterface;
use App\Entity\History as EntityHistory;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;

class History extends HistoryAbstract
{
    public function __construct(
        HistoryManager $manager,
        CurrentUser $currentUser
    ) {
        parent::__construct($manager, $currentUser);
    }

    public function getHistoryRelationEntity()
    {
        return $this->history;
    }

    public function setHistoryRelationEntity(EntityHistory $history)
    {
        $this->history = $history;
    }

    public function compare(array $historyDatas)
    {
        foreach ($historyDatas as $historyData) {
            $diffPresent = false;

            /**
             * @var HistoryData
             */
            $historyData = $historyData;

            switch ($historyData->getTypeOfCompare()) {
                case HistoryTypeOfCompare::FIELD:
                    $this->compareField(
                        $historyData
                    ) && $diffPresent = true;
                    break;
                case HistoryTypeOfCompare::RELATION_ONE_TO_ONE:
                    $this->compareFieldOneToOne(
                        $historyData
                    ) && $diffPresent = true;
                    break;
                case HistoryTypeOfCompare::RELATION_ONE_TO_MANY:
                    $this->compareFieldOneToMany(
                        $historyData
                    ) && $diffPresent = true;
                    break;
                case HistoryTypeOfCompare::ADD_OR_DELETE:
                    $this->ObjectAddOrSupp(
                        $historyData,
                        ($historyData->getOldData()===null?true:false)
                    ) && $diffPresent = true;
                    break;
            }

            if ($diffPresent) {
                $this->save();
            }
        }
    }
}
