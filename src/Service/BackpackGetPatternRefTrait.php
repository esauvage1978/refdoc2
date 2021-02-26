<?php

namespace App\Service;

trait BackpackGetPatternRefTrait
{
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