<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;

use function in_array;

/**
 * Classe permettant l'envoi d'un mail en rapport avec les utilisateurs
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class UserMail
{
    public const LOGIN = 'user/login';
    public const VALIDATE = 'user/validate';
    public const REGISTRATION = 'user/register';
    public const PASSWORDFORGET = 'profil/password_forget';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function send(User $user, string $context, string $subject): int
    {
        if (!in_array($context, [self::LOGIN, self::VALIDATE, self::REGISTRATION, self::PASSWORDFORGET])) {
            return -1;
        }

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user' => $user]);

        return $this->mail->send();
    }
}
