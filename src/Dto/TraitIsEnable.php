<?php

declare(strict_types=1);

namespace App\Dto;

trait TraitIsEnable
{
    /** @var ?String */
    protected $isEnable;

    /**
     * @return mixed
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }

    public function setIsEnable($isEnable)
    {
        $this->checkBool($isEnable);
        $this->isEnable = $isEnable;

        return $this;
    }
}
