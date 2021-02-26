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


    /**
     * @var BackpackRefControllator
     */
    private $controllator;

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

        $this->controllator=new BackpackRefControllator($backpackRepository,$backpack);
    }

    public function get()
    {
        $pattern=$this->getPattern();

        $nbr= ($this->backpackRepository->findCountForRefPattern($pattern)+1);

        while(!$this->controllator->isUnique($pattern.$nbr)) {
            $nbr=$nbr+1;
        }
        return $pattern . $nbr;
    }

}
