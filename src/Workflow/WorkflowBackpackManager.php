<?php

namespace App\Workflow;

use App\Entity\User;
use App\Entity\Backpack;
use App\Security\CurrentUser;
use App\Repository\UserRepository;
use App\Manager\BackpackStateManager;
use App\Event\WorkflowTransitionEvent;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class WorkflowBackpackManager
{
    /**
     * @var BackpackStateManager
     */
    private $backpackStateManager;

    /**
     * @var Registry
     */
    private $workflow;
    /**
     * @var StateMachine
     */
    private $stateMachine;

    /**
     * @var WorkflowBackpackTransitionManager
     */
    private $workflowBackpackTransitionManager;

    /**
     * @var Security
     */
    private $currentUser;

    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        BackpackStateManager $backpackStateManager,
        Registry $workflow,
        CurrentUser $currentUser,
        EventDispatcherInterface $dispatcher,
        UserRepository $userRepository
    ) {
        $this->backpackStateManager = $backpackStateManager;
        $this->currentUser = $currentUser;
        $this->workflow = $workflow;
        $this->dispatcher = $dispatcher;
        $this->userRepository = $userRepository;
    }

    private function initialiseStateMachine(Backpack $item)
    {
        if (null == $this->stateMachine) {
            $this->stateMachine = $this->workflow->get($item, $item->getCategory()->getWorkflowName());
        }
    }

    public function applyTransition(Backpack $item, string $transition, string $content, bool $automate = false)
    {
        $stateOld = $item->getStateCurrent();

        $this->initialiseStateMachine($item);

        if ($this->stateMachine->can($item, $transition)) {
            $this->apply_change_state($item, $transition, $automate, $content);

            $user = $this->loadUser($automate);


            $this->send_mails($user, $item);

            $this->historisation($user, $item, $stateOld);

            return true;
        } else {
            dump('not apply' . $item->getStateCurrent() . ' ' . $transition);
        }

        return false;
    }

    private function apply_change_state(Backpack $item, string $transition, bool $automate, string $content)
    {
        $this->workflowBackpackTransitionManager = new WorkflowBackpackTransitionManager($item, $transition);
        $this->workflowBackpackTransitionManager->intialiseBackpackForTransition($content, $automate);
        $this->stateMachine->apply($item, $transition);
    }

    private function send_mails(User $user, Backpack $item)
    {
        $event = new WorkflowTransitionEvent($user, $item);
        $this->dispatcher->dispatch($event, WorkflowTransitionEvent::NAME);
    }

    private function historisation(User $user, Backpack $item, string $stateOld)
    {
        $this->backpackStateManager->saveActionInHistory($item, $stateOld, $user);
    }

    private function loadUser(bool $automate)
    {
        if (!$automate) {
            return $this->currentUser->getUser();
        } else {
            return $this->userRepository->findAll()[0];
        }
    }
}
