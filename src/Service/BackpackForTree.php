<?php

namespace App\Service;


use App\Dto\UserDto;
use App\Entity\User;
use App\Security\Role;
use App\Dto\BackpackDto;
use App\Tree\BackpackTree;
use App\Security\CurrentUser;
use App\Helper\ParamsInServices;
use App\Repository\BackpackDtoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BackpackForTree
{
    /**
     * @var BackpackMakerDto
     */
    private $backpackMakerDto;

    /**
     * @var User
     */
    private $user;

    /**
     * @var BackpackDtoRepository
     */
    private $backpackDtoRepository;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;


    public function __construct(
        CurrentUser $currentUser,
        BackpackDtoRepository $backpackDtoRepository,
        ParamsInServices $paramsInServices
    ) {
        $this->user = $currentUser->getUser();
        $this->backpackMakerDto = new BackpackMakerDto($this->user);
        $this->backpackDtoRepository = $backpackDtoRepository;
        $this->paramsInServices = $paramsInServices;
    }

    private function getDto(string $filter): BackpackDto
    {
        return $this->backpackMakerDto->get($filter);
    }

    public function getDatas($container, Request $request, string $filter = null, $dto = null): array
    {
        $items = null;
     
        if ($dto === null) {
            $dto = $this->getDto($filter);
        }

        if ($dto->getVisible() === null && $dto->getHide() === null) {
            $dto->setData($request);
            if (!is_null($this->user) && !Role::isGestionnaire($this->user)) {
                $dto->setUserDto((new UserDto())->setId($this->user->getId()));
            }
        }

        if (null !== $dto) {
            $items = $this->backpackDtoRepository->findAllForDto($dto, BackpackDtoRepository::FILTRE_DTO_INIT_TREE);
        }

        $renderArray = $dto->getData();

        $tree = new BackpackTree($container, $request, $this->paramsInServices);

        if (!is_null($dto->getId())) {
            $tree->setItem($this->repository->find($dto->getId()));
        }


        $tree
            ->initialise($items)
            ->setRoute('backpacks')
            ->setParameter($renderArray);

        if (!is_null($dto->getStateCurrent())) {
            $tree->hideState();
        }

        count($items) <= $this->paramsInServices->get(ParamsInServices::ES_TREE_UNDEVELOPPED_NBR) && $tree->Developed();


        return array_merge(
            $renderArray,
            [
                'items' => $tree->getTree(),
                'count' => $tree->getCountItems(),
                'item' => $tree->getItem()
            ]
        );
    }
}
