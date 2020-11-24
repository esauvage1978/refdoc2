<?php

namespace App\History;

use App\Entity\History;
use App\Helper\StackArray;
use App\Manager\HistoryManager;
use App\Security\CurrentUser;
use Symfony\Component\Security\Core\Security;

abstract class HistoryAbstract
{
    CONST TYPE_STRING='string';
    CONST TYPE_BOOL='bool';

    /**
     * @var HistoryManager
     */
    private $manager;

    /**
     * @var History
     */
    protected $history;

    /**
     * @var array
     */
    private $stack;

    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        HistoryManager $manager,
        CurrentUser $currentUser
    )
    {
        $this->manager = $manager;
        $this->currentUser = $currentUser;
        $this->history=new History();
        $this->stack=new StackArray();
    }

    protected function compareField(string $field, ?string $oldData, ?string $newData,string $type=self::TYPE_STRING): bool
    {
        if (!isset($oldData) && !isset($newData) ) {
            return false;
        }

        $oldData!==$newData?$add=true:$add=false;

        switch ($type)
        {
            case self::TYPE_BOOL:
                $oldData=$oldData?'Oui':'Non';
                $newData=$newData?'Oui':'Non';
                break;
        }

        if($add) {
            $this->addContent($field,$oldData,$newData);
            return true;
        }

        return false;
    }


    protected function compareFieldOneToOne(string $field,string $fieldEntity, ?object $oldData, ?object $newData): bool
    {
        if($oldData!==$newData) {
            $func='get'. $fieldEntity;
            $oldDataValue=empty($oldData)?'':($oldData->$func());
            $newDataValue=empty($newData)?'':($newData->$func());
            $this->addContent($field,$oldDataValue,$newDataValue);
            return true;
        }
        return false;
    }


    protected function addContent(string $field, ?string $oldData, ?string $newData)
    {
        $this->stack->push([
           'field'=>$field,
            'oldData'=>(empty($oldData)?'':$oldData),
            'newData'=>(empty($newData)?'':$newData)
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