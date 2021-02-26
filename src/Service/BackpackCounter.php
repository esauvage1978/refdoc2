<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\BackpackDtoRepository;


class BackpackCounter
{

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    private $backpackMakerDto;

    public function __construct(
        BackpackDtoRepository $backpackDtoRepository,
        User $user
    )
    {
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->backpackMakerDto=new BackpackMakerDto($user);
    }

    public function get(string $type,?string $param=null)
    {
        return $this->backpackDtoRepository->countForDto(
            $this->backpackMakerDto->get($type,$param)
        );
    }

}
