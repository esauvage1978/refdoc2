<?php

namespace App\Security;

use App\Entity\Backpack;
use App\Entity\User;
use App\Service\BackpackMakerDto;
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
    const CLASSIFY = 'classify';

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
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE, self::CREATE, self::CLASSIFY])) {
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
            case self::CLASSIFY:
                    return $this->canClassify($backpack, $this->user);                
            case self::CREATE:
                return $this->canCreate($this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Backpack $backpack, User $user)
    {
        if (Role::isUser($this->user)) {
            return true;
        }
    }

    public function canUpdate(Backpack $backpack, User $user)
    {

        if ($backpack->getBackpackMaster() !== null) {
            return false;
        }

        if ($user->getIsDoc()) {
            return true;
        }

        if (!Role::isUser($this->user)) {
            return false;
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
                WorkflowData::STATE_TO_RESUME,
                WorkflowData::STATE_TO_REVISE,
            ];

        $restrict_All =
            [
                WorkflowData::STATE_ABANDONNED,
                WorkflowData::STATE_PUBLISHED,
                WorkflowData::STATE_TO_REVISE,
            ];

        $restrict_ValidatorByCat =
            [
                WorkflowData::STATE_TO_VALIDATE,
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
                (in_array($process, $processes_contributors) ||
                    in_array($process, $processes_validators))
            ) {
                return true;
            } elseif (
                in_array($Mprocess, $mprocesses_contributors) ||
                in_array($Mprocess, $mprocesses_ADDs) ||
                in_array($Mprocess, $mprocesses_validators)
            ) {
                return true;
            }
        }


        //restriction de la modification aux personne ayant des droits
        if (in_array($stateCurrent, $restrict_ValidatorByCat)) {

            if ($process !== null && in_array($process, $processes_validators)) {
                return true;
            } elseif ($backpack->getCategory()->getIsValidatedByADD() && in_array($Mprocess, $mprocesses_ADDs)) {
                return true;
            } elseif (!$backpack->getCategory()->getIsValidatedByADD() && in_array($Mprocess, $mprocesses_validators)) {
                return true;
            }
        }

        //restriction pour les contrôleurs
        if ($stateCurrent === WorkflowData::STATE_TO_CONTROL && $this->user->getIsControl()) {
            return true;
        }

        //restriction pour les contrôleurs
        if ($stateCurrent === WorkflowData::STATE_TO_CHECK && $this->user->getIsDoc()) {
            return true;
        }

        return false;
    }

    public function canClassify(Backpack $backpack, User $user)
    {

        if (!Role::isUser($this->user)) {
            return false;
        }

        if (Role::isAdmin($user)) {
            return true;
        }

        $process = $backpack->getProcess();
        $Mprocess = $backpack->getMProcess();

        $mprocesses_ADDs = $user->getMProcessDirValidators()->toArray();
        $processes_validators = $user->getProcessValidators()->toArray();
        $mprocesses_validators = $user->getMProcessPoleValidators()->toArray();


        if (
            $process !== null &&
                in_array($process, $processes_validators)
        ) {
            return true;
        } elseif (
            in_array($Mprocess, $mprocesses_ADDs) ||
            in_array($Mprocess, $mprocesses_validators)
        ) {
            return true;
        }

        return false;
    }

    public function canDelete(Backpack $backpack, User $user)
    {
        if ($backpack->getBackpackMaster() !== null) {
            return false;
        }

        if ($user->getIsDoc() || Role::isAdmin($user)) {
            return true;
        }

        return false;
    }

    public function canCreate(User $user)
    {
        if ($user->getIsDoc()) {
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
