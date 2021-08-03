<?php

namespace App\Service;

trait BackpackGetPatternRefTrait
{
    public function getPattern()
    {
        $refPattern = '';

        if ($this->backpack->getProcess() !== null) {
            $refPattern = $this->backpack->getProcess()->getRef();
        } else {
            $refPattern = $this->backpack->getMProcess()->getRef();
        }

        return $refPattern . '-' . $this->backpack->getCategory()->getRef() . '-';
    }
}