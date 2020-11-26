<?php

namespace App\EventSubscriber;

use App\Workflow\WorkflowData;
use Symfony\Component\Workflow\Event\GuardEvent;
use App\Workflow\WorkflowBackpackTransitionManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ActionSubscriber.
 */
class WorkflowBackpackEventSubscriber implements EventSubscriberInterface
{


    public function __construct()
    {
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoAbandonned(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_ABANDONNED);
    }

    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToResume(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_RESUME);
    }


    private function onGuard(GuardEvent $event, string $transition)
    {
        /** @var Action $action */
        $action = $event->getSubject();
        $workflowActionTransitionManager = new WorkflowBackpackTransitionManager(
            $event->getSubject(),
            $transition
        );

        if (!$workflowActionTransitionManager->can()) {
            $event->setBlocked(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.action.guard.goAbandonned' => ['onGuardGoAbandonned'],
            'workflow.action.guard.goToResume' => ['onGuardGoToResume'],
        ];
    }
}