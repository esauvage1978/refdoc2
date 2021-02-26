<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;

use App\Entity\Mailer;
use function in_array;

/**
 * Classe permettant l'envoi d'un mail en rapport avec le mailer
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 22/12/2020
 *
 */
class MailerMail
{
    public const BACKPACK = 'mailer/backpack';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function send(User $user,Mailer $mailer, string $context, string $subject): int
    {
        if (!in_array($context, [self::BACKPACK])) {
            throw new \exception('Le context n\est pas présente dans la liste MailerMail');
        }

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user' => $user,'mailer'=>$mailer]);

        return $this->mail->send();
    }
}
