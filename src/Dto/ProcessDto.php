<?php

declare(strict_types=1);

namespace App\Dto;

class ProcessDto extends AbstractDto
{
    use TraitIsEnable;

    /**
     * @var ?string
     */
    private $forUpdate;

    /** @var UserDto|null */
    private $userDto;

    /** @var MProcessDto|null */
    private $mProcessDto;


    public function getMProcessDto(): ?MProcessDto
    {
        return $this->mProcessDto;
    }

    public function setMProcessDto(?MProcessDto $mProcessDto): ProcessDto
    {
        $this->mProcessDto = $mProcessDto;

        return $this;
    }



    /**
     * @return mixed
     */
    public function getForUpdate()
    {
        return $this->forUpdate;
    }

    /**
     * @param mixed $forUpdate
     * @return MProcessDto
     */
    public function setForUpdate($forUpdate)
    {
        $this->forUpdate = $forUpdate;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getUserDto()
    {
        return $this->userDto;
    }

    /**
     * @param mixed $userDto
     * @return MProcessDto
     */
    public function setUserDto($userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }
}
