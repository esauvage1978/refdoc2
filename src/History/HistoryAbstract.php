<?php

namespace App\History;

use App\Entity\History;
use App\Helper\ArrayDiff;
use App\Helper\StackArray;
use App\History\HistoryData;
use App\Security\CurrentUser;
use App\Manager\HistoryManager;
use Symfony\Component\Security\Core\Security;

abstract class HistoryAbstract
{

    /**
     * @var HistoryManager
     */
    private $manager;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var StackArray
     */
    private $stack;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        HistoryManager $manager,
        CurrentUser $currentUser
    ) {
        $this->manager = $manager;
        $this->currentUser = $currentUser;
        $this->history = new History();
        $this->stack = new StackArray();
    }

    protected function compareField(HistoryData $historyData): bool
    {
        if (null === $historyData->getOldData() && null === $historyData->getNewData()) {
            return false;
        }

        $func = 'get' . $historyData->getField();

        switch ($historyData->getTypeOfData()) {
            case HistoryData::TYPE_BOOL:
                $oldData = $historyData->getOldData()->$func() ? 'Oui' : 'Non';
                $newData = $historyData->getNewData()->$func() ? 'Oui' : 'Non';
                break;
            case HistoryData::TYPE_DATE:
                $oldData = $historyData->getOldData()->$func()->format('d/m/Y H:i:s');
                $newData = $historyData->getNewData()->$func()->format('d/m/Y H:i:s');
                break;
            case HistoryData::TYPE_STRING:
                $oldData = $historyData->getOldData()->$func();
                $newData = $historyData->getNewData()->$func();
                break;
        }

        $this->initHistory($oldData,$newData,$historyData);

        return $oldData !== $newData ;
    }

    private function initHistory($oldData,$newData, HistoryData $historyData)
    {
        $this->history
            ->setOldData($oldData)
            ->setNewData($newData)
            ->setTitle($historyData->getTitle())
            ->setCreatedAt(new \DateTime())
            ->setUser($this->currentUser->getUser());

    }

    protected function compareFieldOneToMany(HistoryData $historyData): bool
    {
        $trouve = false;
        $return = false;

        $func = 'get' . $historyData->getField();
        
        foreach ($historyData->getOldData() as $oldData) {
            $trouve = false;
            foreach ($historyData->getNewData() as $newData) {
                if ($oldData->getID() === $newData->getId()) {
                    $trouve = true;
                    break;
                }
            }
            $newHistoryData=clone $historyData;
            $newHistoryData->setOldData($oldData);
            
            if ($trouve) {
                $newHistoryData->setNewData($newData);
                $this->compareFieldOneToOne($newHistoryData) && $return = true;
            } else {
                $newHistoryData
                    ->setNewData(null)
                    ->setTitle($historyData->getTitle() . " : supprimé [" . $oldData->getid() . "]");

                $this->ObjectAddOrSupp($newHistoryData, false) && $return = true;
            }
        }
        foreach ($historyData->getNewData() as $newData) {
            $trouve = false;
            foreach ($historyData->getOldData() as $oldData) {
                if ($oldData->getID() === $newData->getId()) {
                    $trouve = true;
                    break;
                }
            }
            $newHistoryData=clone $historyData;
            $newHistoryData->setNewData($newData);
            if ($trouve) {
                $newHistoryData->setOldData($oldData);
                $this->compareFieldOneToOne($newHistoryData) && $return = true;;
            } else {
                $newHistoryData->setOldData(null)
                ->setTitle($historyData->getTitle() . " : ajouté [" . $newData->getid() . "]");;
                $this->ObjectAddOrSupp($newHistoryData, true) && $return = true;
            }
        }

        return $return;
    }

    private function ObjectAddOrSupp(HistoryData $historyData, $add = true): bool
    {
        $func = 'get' . $historyData->getField();
        $add?$data=$historyData->getNewData():$data=$historyData->getOldData();
        switch ($historyData->getTypeOfData()) {
            case HistoryData::TYPE_BOOL:
                $dataValue = empty($data) ? '' : ($data->$func() ? 'Oui' : 'Non');
                break;
            case HistoryData::TYPE_DATE:
                $dataValue = empty($data) ? '' : ($data->$func()->format('d/m/Y H:i:s'));
                break;
            case HistoryData::TYPE_STRING:
                $dataValue = empty($data) ? '' : ($data->$func());
                break;
        }

        $this->initHistory($add?null:$dataValue,$add?$dataValue:null,$historyData);

        return true;
    }

    protected function compareFieldOneToOne(HistoryData $historyData): bool
    {
        $func = 'get' . $historyData->getField();

        $oldData = null === $historyData->getOldData() ? '' : $historyData->getOldData()->$func();
        $newData = null === $historyData->getNewData() ? '' : ($historyData->getNewData()->$func());

        if ($oldData !== $newData) {
            switch ($historyData->getTypeOfData()) {
                case HistoryData::TYPE_BOOL:
                    $oldData = null=== $oldData ? '' : ($oldData ? 'Oui' : 'Non');
                    $newData = null=== $newData ? '' : ($newData ? 'Oui' : 'Non');
                    break;
                case HistoryData::TYPE_DATE:
                    $oldData = null=== $oldData ? '' : ($oldData->format('d/m/Y H:i:s'));
                    $newData = null=== $newData  ? '' : ($newData->format('d/m/Y H:i:s'));
                    break;
            }

            $this->initHistory($oldData,$newData,$historyData);

            return true ;
        }
        return false;
    }






    protected function save()
    {
        $historyToSave=clone $this->history;
        $historyToSave->setId(null);
        $this->manager->save($historyToSave);
    }
}
