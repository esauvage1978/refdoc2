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
        $this->users = [];
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
            WorkflowData::STATE_TO_RESUME
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
            WorkflowData::STATE_TO_RESUME
        ];

        if (in_array($state, $stateOwner)) {
            $this->getOwner($backpack);
        }


        if (empty($this->users)) {
            return -1;
        }

        $this->saveHistoryOfMail($backpack);

        return $this->backpackMail->sendForUsers(
            $this->users,
            $backpack,
            BackpackMail::TORESUME,
            WorkflowData::getTitleOfMail($state)
        );
    }

    private function saveHistoryOfMail(Backpack $backpack)
    {
        $content = 'Notification lors du changement d\'état aux adresses suivantes :<br/> ';
        
        foreach($this->users as $user) {
            $content = $content . ' ' . $user->getName() . ' (' . $user->getEmail() . ')'; 
        }

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
                $this->users = array_merge([
                    $user->getEmail() => $user->getName(),
                ], $this->users);
            }
        }
    }


    public function getOwner(Backpack $backpack)
    {
        if ($backpack->getOwner()->getIsEnable()) {
            $this->users = array_merge([
                $backpack->getOwner()
            ], $this->users);
        }
    }
}
