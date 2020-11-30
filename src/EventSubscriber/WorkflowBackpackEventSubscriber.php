<?php

namespace App\EventSubscriber;

use App\Entity\Backpack;
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


    /**
     * @param GuardEvent $event
     */
    public function onGuardGoToValidate(GuardEvent $event)
    {
        $this->onGuard($event, WorkflowData::TRANSITION_GO_TO_VALIDATE);
    }

    private function onGuard(GuardEvent $event, string $transition)
    {
        /** @var Backpack $backpack */
        $backpack = $event->getSubject();
        $workflowBackpackTransitionManager = new WorkflowBackpackTransitionManager(
            $backpack,
            $transition
        );
        if (!$workflowBackpackTransitionManager->can()) {
            $event->setBlocked(true);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.wkf_all.guard.goAbandonned' => ['onGuardGoAbandonned'],
            'workflow.wkf_all.guard.goToResume' => ['onGuardGoToResume'],
            'workflow.wkf_all.guard.goToValidate' => ['onGuardGoToValidate'],
            'workflow.wkf_without_doc.guard.goAbandonned' => ['onGuardGoAbandonned'],
            'workflow.wkf_without_doc.guard.goToResume' => ['onGuardGoToResume'],
            'workflow.wkf_without_doc.guard.goToValidate' => ['onGuardGoToValidate'],
            'workflow.wkf_without_control.guard.goAbandonned' => ['onGuardGoAbandonned'],
            'workflow.wkf_without_control.guard.goToResume' => ['onGuardGoToResume'],
            'workflow.wkf_without_control.guard.goToValidate' => ['onGuardGoToValidate'],
            'workflow.wkf_without_doccontrol.guard.goAbandonned' => ['onGuardGoAbandonned'],
            'workflow.wkf_without_doccontrol.guard.goToResume' => ['onGuardGoToResume'],
            'workflow.wkf_without_doccontrol.guard.goToValidate' => ['onGuardGoToValidate'],
        ];
    }
}
