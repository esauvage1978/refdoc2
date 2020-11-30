<?php

namespace App\EventSubscriber;

use App\Entity\Backpack;
use App\Mail\BackpackMail;
use App\Workflow\WorkflowData;
use App\Helper\ParamsInServices;
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
     * @var BackpackRepository
     */
    private $backpackRepository;

    /**
     * @var ParamsInServices
     */
    private $paramsInServices;

    /**
     * @var BackpackMailHistoryManager
     */
    private $backpackMailHistoryManager;

    private $users;

    public function __construct(
        BackpackMail $backpackMail,
        BackpackRepository $backpackRepository,
        ParamsInServices $paramsInServices,
        BackpackMailHistoryManager $backpackMailHistoryManager
    ) {
        $this->backpackMail = $backpackMail;
        $this->backpackRepository = $backpackRepository;
        $this->paramsInServices = $paramsInServices;
        $this->users = new ArrayCollection();
        $this->backpackMailHistoryManager = $backpackMailHistoryManager;
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
        ];

        if (in_array($state, $mailState)) {
            $this->sendMailForBackpack($backpack, $state);
        }
        return 0;
    }

    private function sendMailForBackpack(Backpack $backpack, string $state)
    {
        if (!$this->checkMailForState($state)) {
            dump('checkMailForState ko');
            return -1;
        }


        $stateOwner = [
            WorkflowData::STATE_TO_RESUME,
            WorkflowData::STATE_TO_VALIDATE,
        ];

        $stateForValidator = [
            WorkflowData::STATE_TO_VALIDATE,
        ];

        if (in_array($state, $stateOwner)) {
            dump('owner');
            $this->getOwner($backpack);
        }
        if (in_array($state, $stateForValidator)) {
            dump('to validate');
            $this->getUserForValidator($backpack);
        }
        dump($this->users);
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
        foreach ($backpack->getMProcess()->getDirValidators() as $user) {
            if ($user->getIsEnable()) {
                if (!$this->users->contains($user)) {
                    $this->users[] = $user;
                }
            }
        }
    }
    public function getOwner(Backpack $backpack)
    {
        if ($backpack->getOwner()->getIsEnable()) {
            if (!$this->users->contains($backpack->getOwner())) {
                $this->users[] = $backpack->getOwner();
            }
        }
    }

    public function getUserForValidator(Backpack $backpack)
    {
        if ($backpack->getProcess() !== null) {
            foreach ($backpack->getProcess()->getValidators() as $user) {
                if ($user->getIsEnable()) {
                    if (!$this->users->contains($user)) {
                        $this->users[] = $user;
                    }
                }
            }
        } elseif ($backpack->getCategory()->getIsValidatedByADD()) {
            foreach ($backpack->getMProcess()->getDirValidators() as $user) {
                if ($user->getIsEnable()) {
                    if (!$this->users->contains($user)) {
                        $this->users[] = $user;
                    }
                }
            }
        } else {
            foreach ($backpack->getMProcess()->getPoleValidators() as $user) {
                if ($user->getIsEnable()) {
                    if (!$this->users->contains($user)) {
                        $this->users[] = $user;
                    }
                }
            }
        }
    }
}
