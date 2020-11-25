<?php

namespace App\Event;

use App\Entity\Backpack;
use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class WorkflowTransitionEvent extends Event
{
    public const NAME = 'worklow.transition';

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Backpack
     */
    protected $backpack;

    public function __construct(
        User $user,
        Backpack $backpack)
    {
        $this->user = $user;
        $this->backpack=$backpack;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * @return $backpack
     */
    public function getAction()
    {
        return $this->backpack;
    }
}
