<?php

namespace App\History;

use App\Entity\History;
use App\Helper\ArrayDiff;
use App\Helper\StackArray;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
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

    protected function compareField($title, $field, $oldData, $newData, $typeOfData): bool
    {
        if (!isset($oldData) && !isset($newData)) {
            return false;
        }

        $func = 'get' . $field;
        switch ($typeOfData) {
            case HistoryData::TYPE_BOOL:
                $oldData = $oldData->$func() ? 'Oui' : 'Non';
                $newData = $newData->$func() ? 'Oui' : 'Non';
                break;
            case HistoryData::TYPE_DATE:
                $oldData = $oldData->$func()->format('d/m/Y H:i:s');
                $newData = $newData->$func()->format('d/m/Y H:i:s');
                break;
            case HistoryData::TYPE_STRING:
                $oldData = $oldData->$func();
                $newData = $newData->$func();
                break;
        }

        $oldData !== $newData ? $add = true : $add = false;
        if ($add) {
            $this->addContent($title, $oldData, $newData);
            return true;
        }

        return false;
    }


    protected function compareFieldOneToOne(string $title, string $field, ?object $oldData, ?object $newData, $typeOfData): bool
    {
        $func = 'get' . $field;
        $oldDataValue = empty($oldData) ? '' : ($oldData->$func());
        $newDataValue = empty($newData) ? '' : ($newData->$func());
        if ($oldDataValue !== $newDataValue) {
            switch ($typeOfData) {
                case HistoryData::TYPE_BOOL:
                    $oldDataValue = empty($oldData) ? '' : ($oldData->$func() ? 'Oui' : 'Non');
                    $newDataValue = empty($newData) ? '' : ($newData->$func() ? 'Oui' : 'Non');
                    break;
                case HistoryData::TYPE_DATE:
                    $oldDataValue = empty($oldData) ? '' : ($oldData->$func()->format('d/m/Y H:i:s'));
                    $newDataValue = empty($newData) ? '' : ($newData->$func()->format('d/m/Y H:i:s'));
                    break;
            }
            $this->addContent($title, $oldDataValue, $newDataValue);
            return true;
        }
        return false;
    }

    private function ObjectAddOrSupp(string $title, string $field, $data, $typeOfData, $add = true): bool
    {
        $func = 'get' . $field;
        switch ($typeOfData) {
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
        $this->addContent($title, $add ? '' : $dataValue, $add ? $dataValue : '');
        return true;
    }

    protected function compareFieldOneToMany(string $title, string $field, $oldData, $newData, $typeOfData): bool
    {
        $trouve = false;
        $return = false;
        $func = 'get' . $field;
        foreach ($oldData as $oneOldData) {
            $trouve = false;
            foreach ($newData as $oneNewData) {
                if ($oneOldData->getID() === $oneNewData->getId()) {
                    $trouve = true;
                    break;
                }
            }
            if ($trouve) {
                $this->compareFieldOneToOne($title, $field, $oneOldData, $oneNewData, $typeOfData) && $return = true;
            } else {
                $this->ObjectAddOrSupp($title . " : supprimé [" . $oneOldData->getid() . "]", $field, $oneOldData, $typeOfData, false) && $return = true;
            }
        }
        foreach ($newData as $oneNewData) {
            $trouve = false;
            foreach ($oldData as $oneOldData) {
                if ($oneOldData->getID() === $oneNewData->getId()) {
                    $trouve = true;
                    break;
                }
            }
            if ($trouve) {
                $this->compareFieldOneToOne($title, $field, $oneOldData, $oneNewData, $typeOfData) && $return = true;
            } else {
                $this->ObjectAddOrSupp($title . " : ajouté [" . $oneNewData->getid() . "]", $field, $oneNewData, $typeOfData) && $return = true;
            }
        }
        return $return;
    }
    protected function addContent(string $title, ?string $oldData, ?string $newData)
    {
        $this->stack->push([
            'field' => $title,
            'oldData' => (empty($oldData) ? '' : $oldData),
            'newData' => (empty($newData) ? '' : $newData)
        ]);
    }

    protected function save()
    {
        $this->history
            ->setCreatedAt(new \DateTime())
            ->setUser($this->currentUser->getUser())
            ->setContent($this->stack->toArray());

        $this->manager->save($this->history);
    }
}
