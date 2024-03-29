<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Backpack;
use App\Mail\BackpackMail;
use App\Workflow\WorkflowData;
use App\Helper\ParamsInServices;
use App\Repository\UserRepository;
use App\Entity\BackpackMailHistory;
use App\Event\WorkflowTransitionEvent;
use App\Repository\BackpackRepository;
use App\Manager\BackpackMailHistoryManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class WorkflowMailerSubscriber implements EventSubscriberInterface
{
    /**
     * @var BackpackMail
     */
    private $backpackMail;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    /**
     * @var BackpackMailHistoryManager
     */
    private $backpackMailHistoryManager;


    /**
     * @var UserRepository
     */
    private $userRepository;

    private $users;

    public function __construct(
        BackpackMail $backpackMail,
        ParamsInServices $paramsInServices,
        BackpackMailHistoryManager $backpackMailHistoryManager,
        UserRepository $userRepository
    ) {
        $this->backpackMail = $backpackMail;
        $this->paramsInServices = $paramsInServices;
        $this->users = new ArrayCollection();
        $this->backpackMailHistoryManager = $backpackMailHistoryManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkflowTransitionEvent::NAME => 'onWorklowTransitionEvent',
        ];
    }

    public function onWorklowTransitionEvent(WorkflowTransitionEvent $event): int
    {

        /** @var Backpack $backpack */
        $backpack = $event->getAction();
        /** @var string $state */
        $state = $backpack->getStateCurrent();


        $mailState = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_TO_CONTROL,
            WorkflowData::STATE_TO_CHECK,
            WorkflowData::STATE_PUBLISHED,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
        ];

        if (in_array($state, $mailState)) {
            $this->sendMailForBackpack($backpack, $state);
        }
        return 0;
    }

    private function sendMailForBackpack(Backpack $backpack, string $state)
    {
        if (!$this->checkMailForState($state)) {
            return -1;
        }


        $stateOwner = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_TO_CONTROL,
            WorkflowData::STATE_TO_CHECK,
            WorkflowData::STATE_PUBLISHED,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
            WorkflowData::STATE_ARCHIVED,
        ];

        $stateForContributor = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_REVISE,
            WorkflowData::STATE_IN_REVIEW,
        ];

        $stateForValidator = [
            WorkflowData::STATE_TO_VALIDATE,
            WorkflowData::STATE_PUBLISHED,
        ];


        if (in_array($state, $stateOwner)) {
            $this->getOwner($backpack);
        }
        if (in_array($state, $stateForValidator)) {
            $this->getUserForValidator($backpack);
        }
        if (in_array($state, $stateForContributor)) {
            $this->getUserForContributor($backpack);
        }
        if ($state === WorkflowData::STATE_TO_CONTROL) {
            $this->getUsersControl();
        }
        if ($state === WorkflowData::STATE_TO_CHECK) {
            $this->getUsersDoc();
        }


        if ($this->users->isEmpty()) {
            return -1;
        }

        $this->saveHistoryOfMail($backpack);

        return $this->backpackMail->sendForUsers(
            $this->users,
            $backpack,
            $state,
            WorkflowData::getTitleOfMail($state)
        );
    }

    private function saveHistoryOfMail(Backpack $backpack)
    {
        $content = 'Notification lors du changement d\'état aux adresses suivantes :<br/><ul> ';

        foreach ($this->users as $user) {
            $content = $content . '<li>' . $user->getName() . ' (' . $user->getEmail() . ')</li>';
        }
        $content = $content . '</ul>';
        $backpackMailHistory = new BackpackMailHistory();
        $backpackMailHistory
            ->setBackpack($backpack)
            ->setContent($content)
            ->setSendAt(new \DateTime());
        $this->backpackMailHistoryManager->save($backpackMailHistory);
    }

    private function checkMailForState(string $state): bool
    {
        $parameter = 'es.mailer.workflow.' . $state;
        return $this->paramsInServices->get($parameter);
    }

    public function getUserMprocessValider(Backpack $backpack)
    {
        $this->addUsers($backpack->getMProcess()->getDirValidators());
    }
    public function getOwner(Backpack $backpack)
    {

        $this->addUser($backpack->getOwner());
    }

    public function getUsersControl()
    {
        $this->addUsers($this->userRepository->findAllForControl());
    }

    public function getUsersDoc()
    {
        $this->addUsers($this->userRepository->findAllForDoc());
    }

    public function getUserForValidator(Backpack $backpack)
    {
        if ($backpack->getProcess() !== null) {
            $this->addUsers($backpack->getProcess()->getValidators());
        } elseif ($backpack->getCategory()->getIsValidatedByADD()) {
            $this->addUsers($backpack->getMProcess()->getDirValidators());
        } else {
            $this->addUsers($backpack->getMProcess()->getPoleValidators());
        }
    }

    public function getUserForContributor(Backpack $backpack)
    {
        if ($backpack->getProcess() !== null) {
            $this->addUsers($backpack->getProcess()->getContributors());
        } else {
            $this->addUsers($backpack->getMProcess()->getContributors());
        }
    }

    private function addUser(User $user)
    {
        if ($user->getIsEnable()) {
            if (!$this->users->contains($user)) {
                $this->users[] = $user;
            }
        }
    }

    private function addUsers($users)
    {
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }
}
