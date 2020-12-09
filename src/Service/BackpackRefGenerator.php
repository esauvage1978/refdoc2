<?php

namespace App\Service;

use App\Entity\Backpack;
use App\Repository\BackpackRepository;


class BackpackRefGenerator
{
    use BackpackGetPatternRefTrait;

    /**
     * @var BackpackRepository
     */
    private $backpackRepository;

    private $backpackMakerDto;

    /**
     * @var Backpack
     */
    private $backpack;

    public function __construct(
        BackpackRepository $backpackRepository,
        Backpack $backpack
    ) {
        $this->backpackRepository = $backpackRepository;
        $this->backpack=$backpack;
    }

    public function get()
    {
        $pattern=$this->getPattern();
            return $pattern . ($this->backpackRepository->findCountForRefPattern($pattern)+1);
    }

}
