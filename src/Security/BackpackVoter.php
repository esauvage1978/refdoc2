<?php

namespace App\Security;

use App\Entity\Backpack;
use App\Entity\User;
use App\Workflow\WorkflowData;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class BackpackVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const CREATE = 'create';

    /**
     * @var User|null $currentUser
     */
    private $user;

    public function __construct(CurrentUser $currentUser)
    {
        $this->user = $currentUser->getUser();
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE, self::CREATE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if ($attribute != self::CREATE) {
            if (null !== $subject and !$subject instanceof Backpack) {
                return false;
            }
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        if (!$this->user instanceof User) {
            return false;
        }

        /** @var Backpack $backpack */
        $backpack = $subject;

        switch ($attribute) {
            case self::READ:
                return $this->canRead($backpack, $this->user);
            case self::UPDATE:
                return $this->canUpdate($backpack, $this->user);
            case self::DELETE:
                return $this->canDelete($backpack, $this->user);
            case self::CREATE:
                return $this->canCreate($this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Backpack $backpack, User $user)
    {
        if (Role::isGestionnaire($this->user)) {
            return true;
        }

        return $this->canUpdate($backpack, $user);
    }

    public function canUpdate(Backpack $backpack, User $user)
    {
        if ($user->getUserparam()->getIsDoc()) {
            return true;
        }

        if (!Role::isUser($this->user)) {
            return true;
        }

        $stateCurrent = $backpack->getStateCurrent();
        $process = $backpack->getProcess();
        $Mprocess = $backpack->getMProcess();

        $processes_contributors = $user->getProcessContributors()->toArray();
        $mprocesses_contributors = $user->getMProcessContributors()->toArray();
        $mprocesses_ADDs = $user->getMProcessDirValidators()->toArray();
        $processes_validators = $user->getProcessValidators()->toArray();
        $mprocesses_validators = $user->getMProcessPoleValidators()->toArray();


        $restrict_contributer =
            [
                WorkflowData::STATE_DRAFT,
                WorkflowData::STATE_TO_RESUME
            ];

        $restrict_All =
            [
                WorkflowData::STATE_ABANDONNED,
            ];

        //restriction de la modification aux contributeurs
        if (in_array($stateCurrent, $restrict_contributer)) {

            if ($process !== null && in_array($process, $processes_contributors)) {
                return true;
            } elseif (in_array($Mprocess, $mprocesses_contributors)) {
                return true;
            }
        }

        //restriction de la modification aux personne ayant des droits
        if (in_array($stateCurrent, $restrict_All)) {

            if (
                $process !== null &&
                (in_array($process, $processes_contributors) or
                    in_array($process, $processes_validators))
            ) {
                return true;
            } elseif (
                in_array($Mprocess, $mprocesses_contributors) or
                in_array($Mprocess, $mprocesses_ADDs) or
                in_array($Mprocess, $mprocesses_validators)
            ) {
                return true;
            }
        }

        return false;
    }

    public function canDelete(Backpack $backpack, User $user)
    {
        if ($user->getUserparam()->getIsDoc() || Role::isAdmin($user)) {
            return true;
        }

        return false;
    }

    public function canCreate(User $user)
    {
        if ($user->getUserparam()->getIsDoc()) {
            return true;
        }

        $nbr_P = count($user->getProcessContributors());
        $nbr_MP = count($user->getMProcessContributors());

        if ($nbr_MP > 0 or $nbr_P > 0) {
            return true;
        }

        return false;
    }
}
