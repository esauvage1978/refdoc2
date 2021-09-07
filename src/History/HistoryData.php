<?php

namespace App\History;

use App\Entity\EntityInterface;


class HistoryData
{
    const TYPE_STRING = 'string';
    const TYPE_BOOL = 'bool';
    const TYPE_DATE = 'date';

    /**
     * @var string
     */
    private $title;

    /**
     * @var string|null|EntityInterface
     */
    private $oldData;

    /**
     * @var string|null|EntityInterface 
     */
    private $newData;

    /**
     * @var string
     */
    private $typeOfCompare;

    /**
     * @var string
     */
    private $field;

    /**
     * @var string
     */
    private $domaine;

    /**
     * @var string
     */
    private $typeOfData;

    /**
     * @var EntityInterface
     */
    private $entityMaster;

    public function __construct()
    {
        $this->typeOfCompare = HistoryTypeOfCompare::FIELD;
        $this->typeOfData = self::TYPE_STRING;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getEntityMaster(): EntityInterface
    {
        return $this->entityMaster;
    }

    public function setEntityMaster(EntityInterface $entityMaster): self
    {
        $this->entityMaster = $entityMaster;

        return $this;
    }

    public function getDomaine(): string
    {
        return $this->domaine;
    }

    public function setDomaine(string $domaine): self
    {
        $this->domaine = $domaine;

        return $this;
    }

    public function getOldData()
    {
        return $this->oldData;
    }

    public function setOldData($oldData): self
    {
        $this->oldData = $oldData;

        return $this;
    }

    public function getNewData()
    {
        return $this->newData;
    }

    public function setNewData($newData): self
    {
        $this->newData = $newData;

        return $this;
    }

    public function getTypeOfCompare(): string
    {
        return $this->typeOfCompare;
    }

    public function setTypeOfCompare(string $typeOfCompare): self
    {
        $this->typeOfCompare = $typeOfCompare;

        return $this;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function setField(string $field): self
    {
        $this->field = $field;

        return $this;
    }

    public function getTypeOfData(): string
    {
        return $this->typeOfData;
    }

    public function setTypeOfData(string $typeOfData): self
    {
        $this->typeOfData = $typeOfData;

        return $this;
    }
}
