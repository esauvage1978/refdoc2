<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use InvalidArgumentException;

use function in_array;

/**
 * Liste les différents rôles 
 *
 * (c) Emmanuel Sauvage <emmanuel.sauvage@live.fr>
 * 24/07/2020
 *
 */
final class Role
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_GESTIONNAIRE = 'ROLE_GESTIONNAIRE';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    public static function hasData(string $data): bool
    {
        $datas = [
            self::ROLE_USER,
            self::ROLE_GESTIONNAIRE,
            self::ROLE_ADMIN,
        ];

        if (in_array($data, $datas)) {
            return true;
        }

        return false;
    }

    public static function checkData(string $data)
    {
        if (!Role::hasData($data)) {
            throw new InvalidArgumentException('Ce rôle est incconnu : ' . $data);
        }
    }

    public static function getDatas(): array
    {
        return [
            'Utilisateur' => self::ROLE_USER,
            'Gestionnaire' => self::ROLE_GESTIONNAIRE,
            'Administrateur' => self::ROLE_ADMIN,
        ];
    }

    /**
     * Défini si l'utilisateur est administrateur
     */
    public static function isAdmin(?User $user): bool
    {
        return $user !== null and self::hasAdmin($user);
    }


    /**
     * Défini si l'utilisateur est administrateur dans la liste de ses rôles
     */
    public static function hasAdmin(?User $user): bool
    {
        return  $user !== null  and in_array(self::ROLE_ADMIN, $user->getRoles());
    }

    /**
     * Défini si l'utilisateur est gestionnaire
     */
    public static function isGestionnaire(?User $user): bool
    {
        return $user !== null  and (self::hasGestionnaire($user) or self::isAdmin($user));
    }

    /**
     * Défini si l'utilisateur est gestionnaire dans la liste de ses rôles
     */
    public static function hasGestionnaire(?User $user): bool
    {
        return $user !== null  and in_array(self::ROLE_GESTIONNAIRE, $user->getRoles());
    }

    /**
     * Défini si l'utilisateur a l'habilitation utilisateur
     */
    public static function isUser(?User $user): bool
    {
        return $user !== null  and (self::hasUser($user) or self::isGestionnaire($user) or self::isAdmin($user));
    }


    /**
     * Défini si l'utilisateur a l'habilitation utilisateur dans la liste de ses rôles
     */
    public static function hasUser(?User $user): bool
    {
        return  $user !== null  and in_array(self::ROLE_USER, $user->getRoles());
    }
}
