<?php

namespace App\Service;

use App\Entity\Backpack;
use App\Repository\BackpackRepository;


class BackpackGenerateRef
{

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

    public function getPattern()
    {
        $refPattern = '';

        if ($this->backpack->getMProcess() !== null) {
            $refPattern = $this->backpack->getMProcess()->getRef();
        } else {
            $refPattern = $this->backpack->getProcess()->getRef();
        }

        return $refPattern . '-' . $this->backpack->getCategory()->getRef() . '-';
    }
}
