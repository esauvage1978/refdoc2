<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class IconExtension extends AbstractExtension
{
    /** @var string[] */
    private static $icons = [
        'about' => 'fas fa-info-circle',
        'activity' => 'fas fa-tasks',
        'admin' => 'fas fa-wrench',
        'administrateur' => 'fa fa-user-astronaut',
        'audit' => 'fas fa-history',
        'avatar' => 'fab fa-snapchat-ghost',
        'back' => 'fas fa-long-arrow-alt-left',
        'backpack' => 'fas fa-suitcase',
        'category' => 'fas fa-clipboard-list',
        'calendar' => 'far fa-calendar-alt',
        'check_big' => 'far fa-check-circle fa-4x',
        'clock' => 'far fa-clock',
        'configuration' => 'fas fa-cogs',
        'contact' => 'fas fa-users',
        'control' => 'fas fa-user-shield',
        'copy' => 'far fa-copy',
        'create' => 'far fa-plus-square',
        'csv' => 'fas fa-table',
        'customer' => 'fas fa-user-tie',
        'comment' => 'fas fa-comment-dots',
        'comments' => 'fas fa-comments',
        'dashboard' => 'fas fa-tachometer-alt',
        'debug' => 'far fa-file-alt',
        'delete' => 'fas fa-trash',
        'doctor' => 'fas fa-medkit',
        'documentation' => 'fas fa-user-tag',
        'dot' => 'fas fa-circle',
        'download' => 'fas fa-download',
        'duration' => 'far fa-hourglass',
        'edit' => 'far fa-edit',
        'end' => 'fas fa-stopwatch',
        'export' => 'fas fa-file-export',
        'fax' => 'fas fa-fax',
        'gestionnaire' => 'fa fa-user-secret',
        'filter' => 'fas fa-filter',
        'gpi' => 'fas fa-bullhorn',
        'help' => 'far fa-question-circle',
        'history' => 'fas fa-history',
        'home' => 'fas fa-home',
        'invoice' => 'fas fa-file-invoice-dollar',
        'invoice-template' => 'fas fa-file-signature',
        'left' => 'fas fa-chevron-left',
        'list' => 'fas fa-list',
        'locked' => 'fas fa-lock',
        'logout' => 'fas fa-sign-out-alt',
        'mail' => 'fas fa-envelope-open',
        'mail-sent' => 'fas fa-paper-plane',
        'manual' => 'fas fa-book',
        'mobile' => 'fas fa-mobile',
        'money' => 'far fa-money-bill-alt',
        'mprocess' => 'fas fa-sitemap', 
        'nocheck_big' => 'far fa-times-circle fa-4x',
        'ods' => 'fas fa-table',
        'off' => 'fas fa-toggle-off',
        'on' => 'fas fa-toggle-on',
        'password' => 'fas fa-key',
        'pin' => 'fas fa-thumbtack',
        'pdf' => 'fas fa-file-pdf',
        'pause' => 'fas fa-pause',
        'pause-small' => 'far fa-pause-circle',
        'permissions' => 'fas fa-user-lock',
        'phone' => 'fas fa-phone',
        'plugin' => 'fas fa-plug',
        'print' => 'fas fa-print',
        'process' => 'fas fa-square',
        'profil' => 'fas fa-user',
        'profile' => 'fas fa-user-edit',
        'profile-stats' => 'far fa-chart-bar',
        'project' => 'fas fa-briefcase',
        'repeat' => 'fas fa-redo-alt',
        'right' => 'fas fa-chevron-right',
        'roles' => 'fas fa-user-shield',
        'save' => 'fas fa-save',
        'search' => 'fas fa-search',
        'settings' => 'fas fa-cog',
        'shop' => 'fas fa-shopping-cart',
        'show' => 'fas fa-eye',
        'sort' => 'fas fa-sort-amount-down-alt',
        'start' => 'fas fa-play',
        'start-small' => 'far fa-play-circle',
        'stop' => 'fas fa-stop',
        'stop-small' => 'far fa-stop-circle',
        'subscription' => 'fab fa-chromecast',
        'success' => 'fas fa-check',
        'tag' => 'fas fa-tags',
        'team' => 'fas fa-users',
        'timesheet' => 'fas fa-clock',
        'timesheet-team' => 'fas fa-user-clock',
        'trash' => 'far fa-trash-alt',
        'unlocked' => 'fas fa-unlock-alt',
        'upload' => 'fas fa-upload',
        'user' => 'fas fa-user-friends',
        'visibility' => 'far fa-eye',
        'warning' => 'fas fa-exclamation-triangle',
        'workflow' => 'fas fa-bezier-curve',
        'xlsx' => 'fas fa-file-excel',

    ];

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new TwigFilter('icon', [$this, 'icon']),
        ];
    }

    public function icon(string $name, string $default = ''): string
    {
        return self::$icons[$name] ?? $default;
    }
}
