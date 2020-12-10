<?php

namespace App\Listener;

use App\Entity\User;
use App\Mail\UserMail;
use App\Manager\UserManager;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class UserLoginListener
{
    /**
     * @var UserMail
     */
    private $sendmail;

    /**
     * @var UserManager
     */
    private $userManager;

    public function __construct(
        UserMail $sendmail,
        UserManager $userManager
    ) {
        $this->sendmail = $sendmail;
        $this->userManager = $userManager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event): ?int
    {
        /** @var User $user */
        $user = $event->getAuthenticationToken()->getUser();

        $user->setLoginAt(new \DateTime());
        $this->userManager->save($user);


        if (!$user->getEmailValidated()) {
            return $this->sendmail->send(
                $user,
                $this->sendmail::LOGIN,
                'Connexion effectuée');
        }

        return null;
    }
}
