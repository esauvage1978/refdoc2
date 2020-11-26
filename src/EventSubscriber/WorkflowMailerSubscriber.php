<?php

namespace App\EventSubscriber;

use App\Entity\Backpack;
use App\Mail\BackpackMail;
use App\Workflow\WorkflowData;
use App\Helper\ParamsInServices;
use App\Event\WorkflowTransitionEvent;
use App\Repository\BackpackRepository;
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

    private $users;

    public function __construct(
        BackpackMail $backpackMail,
        BackpackRepository $backpackRepository,
        ParamsInServices $paramsInServices
    ) {
        $this->backpackMail = $backpackMail;
        $this->backpackRepository = $backpackRepository;
        $this->paramsInServices = $paramsInServices;
        $this->users=[];
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
            dump('owner');
            $this->getOwner($backpack);
            dump($this->users);
        }


        if (empty($this->users)) {
            dump('user vide');
            return -1;
        }

        return $this->backpackMail->sendForUsers(
            $this->users,
            $backpack,
            BackpackMail::TORESUME,
            WorkflowData::getTitleOfMail($state)
        );
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
