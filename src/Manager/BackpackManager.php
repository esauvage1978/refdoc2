<?php

namespace App\Manager;

use App\Entity\Backpack;
use App\Entity\EntityInterface;
use App\Security\CurrentUser;
use App\Validator\BackpackValidator;
use App\Workflow\WorkflowData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class BackpackManager extends AbstractManager
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackValidator $validator,
        CurrentUser $currentUser
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Backpack $bp
         */
        $bp = $entity;


        if (null===$entity->getId()) {
            $bp->setOwner($this->currentUser->getUser());
        } else {
            $bp->setUpdatedAt(new \DateTime());
        }

        if ($bp->getProcess() !== null) {
            $bp->setMProcess($bp->getProcess()->getMProcess());
        }
    }
}
