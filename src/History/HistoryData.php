<?php

namespace App\History;


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
    private $dataOld;

    /**
     * @var string|null|EntityInterface
     */
    private $dataNew;

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
    private $typeOfData;

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

    public function getDataOld()
    {
        return $this->dataOld;
    }

    public function setDataOld($dataOld): self
    {
        $this->dataOld = $dataOld;

        return $this;
    }

    public function getDataNew()
    {
        return $this->dataNew;
    }

    public function setDataNew($dataNew): self
    {
        $this->dataNew = $dataNew;

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
