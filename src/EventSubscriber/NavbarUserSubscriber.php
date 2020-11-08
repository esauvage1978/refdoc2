<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Security\CurrentUser;
use KevinPapst\AdminLTEBundle\Event\NavbarUserEvent;
use KevinPapst\AdminLTEBundle\Event\ShowUserEvent;
use KevinPapst\AdminLTEBundle\Event\SidebarUserEvent;
use KevinPapst\AdminLTEBundle\Model\UserModel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

use function assert;

class NavbarUserSubscriber implements EventSubscriberInterface
{
    /** @var CurrentUser */
    protected $currentUser;

    /**
     * @param Security $security
     */
    public function __construct(CurrentUser $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            NavbarUserEvent::class => ['onShowUser', 100],
            SidebarUserEvent::class => ['onShowUser', 100],
        ];
    }

    public function onShowUser(ShowUserEvent $event): void
    {
        if ($this->currentUser->getUser() === null) {
            return;
        }

        $myUser = $this->currentUser->getUser();
        assert($myUser instanceof User);

        $user = new UserModel();
        $user
            ->setId($myUser->getId())
            ->setName($myUser->getName())
            ->setUsername($myUser->getName())
            ->setIsOnline(true)
            ->setTitle('')
            ->setAvatar($myUser->getAvatar())
            ->setMemberSince($myUser->getCreatedAt());

        $event->setShowLogoutLink(true);
        $event->setShowProfileLink(true);

        $event->setUser($user);
    }
}
