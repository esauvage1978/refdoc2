<?php

namespace App\Manager;

use App\Entity\Backpack;
use App\Entity\BackpackState;
use App\Entity\EntityInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\BackpackStateValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class BackpackStateManager extends AbstractManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackStateValidator $validator,
        UserRepository $userRepository
    ) {
        parent::__construct($manager, $validator);
        $this->userRepository = $userRepository;

    }

    public function saveActionInHistory(Backpack $item,string $initial_state, User $user)
    {

        $actionState = new BackpackState();
        $actionState
            ->setUser($user)
            ->setBackpack($item)
            ->setContent($item->getStateContent())
            ->setChangeAt(new \DateTime())
            ->setStateOld($initial_state)
            ->setStateNew($item->getStateCurrent());

        $this->save($actionState);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
