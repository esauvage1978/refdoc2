<?php

namespace App\Twig;

use App\Entity\Backpack;
use App\Entity\User;
use App\Security\BackpackVoter;
use App\Security\CurrentUser;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class BackpackExtension extends AbstractExtension
{
    /**
     * @var BackpackVoter
     */
    private $backpackVoter;

    /**
     * @var User
     */
    protected $user;


    /**
     * BackpackExtension constructor.
     * @param CurrentUser $user
     * @param BackpackVoter $backpackVoter
     */
    public function __construct(
        CurrentUser $user,
        BackpackVoter $backpackVoter) {
        $this->user = $user->getUser();

        $this->backpackVoter=$backpackVoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('backpackCanRead', [$this, 'backpackCanRead']),
            new TwigFilter('backpackCanUpdate', [$this, 'backpackCanUpdate']),
            new TwigFilter('backpackCanDelete', [$this, 'backpackCanDelete']),
        ];
    }



    public function backpackCanRead(Backpack $item)
    {
        return $this->backpackVoter->canRead($item, $this->user);
    }

    public function backpackCanUpdate(Backpack $item)
    {
        return $this->backpackVoter->canUpdate($item, $this->user);
    }

    public function backpackCanDelete(Backpack $item)
    {
        return $this->backpackVoter->canDelete($item, $this->user);
    }
}
