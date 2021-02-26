<?php

namespace App\Service;

use App\Entity\Backpack;
use App\Repository\BackpackRepository;


class BackpackRefControllator
{
    use BackpackGetPatternRefTrait;

    /**
     * @var BackpackRepository
     */
    private $backpackRepository;

    /**
     * @var Backpack
     */
    private $backpack;

    public function __construct(
        BackpackRepository $backpackRepository,
        Backpack $backpack
    ) {
        $this->backpackRepository = $backpackRepository;
        $this->backpack = $backpack;
    }

    public function isUnique(string $ref)
    {
        return $this->backpackRepository->findCountForRef($ref,$this->backpack->getId())==="0"?true:false;
    }

    public function isCoherent()
    {
        return strpos($this->backpack->getRef(), $this->getPattern()) === false?false:true;
    }
}
