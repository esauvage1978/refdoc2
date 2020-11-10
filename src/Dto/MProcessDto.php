<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\UserDto;

class MProcessDto extends AbstractDto
{
    use TraitIsEnable;

    /**
     * @var ?string
     */
    private $forUpdate;

    /** @var UserDto|null */
    private $userDto;

    /** @var ProcessDto|null */
    private $processDto;


    public function getProcessDto(): ?ProcessDto
    {
        return $this->processDto;
    }

    public function setProcessDto(ProcessDto $processDto): MProcessDto
    {
        $this->processDto = $processDto;

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
