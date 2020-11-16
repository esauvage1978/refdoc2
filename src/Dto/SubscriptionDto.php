<?php

declare(strict_types=1);

namespace App\Dto;

class SubscriptionDto extends AbstractDto
{
    /** @var UserDto|null */
    private $userDto;

    public function getUserDto(): ?UserDto
    {
        return $this->userDto;
    }

    public function setUserDto(?UserDto $userDto): SubscriptionDto
    {
        $this->userDto = $userDto;

        return $this;
    }
}
