<?php

declare(strict_types=1);

namespace App\Mail;

use App\Entity\User;

use function in_array;
use App\Entity\Backpack;

/**
 * Classe permettant l'envoi d'un mail en rapport avec les utilisateurs
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
class BackpackMail
{
    public const TORESUME = 'workflow/toresume';

    private $mail;

    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    public function send(User $user, Backpack $backpack, string $context, string $subject): int
    {
        if (!in_array($context, [self::TORESUME])) {
            throw new \exception('Le context n\est pas présente dans la liste UserMail');
        }

        $this->mail
            ->setUserTo($user)
            ->setContext($context)
            ->setSubject($subject)
            ->setParamsTwig(['user' => $user, 'backpack' => $backpack]);

        return $this->mail->send();
    }

    public function sendForUsers(array $users, Backpack $backpack, string $context, string $subject)
    {
        foreach ($users as $user) {
            $this->send($user, $backpack, $context, $subject);
        }
    }
}
