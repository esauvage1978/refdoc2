<?php

namespace App\Manager;

use App\Entity\Backpack;
use App\Security\CurrentUser;

use App\Entity\EntityInterface;
use App\History\BackpackHistory;
use App\Repository\BackpackRepository;
use App\Service\BackpackRefGenerator;
use App\Validator\BackpackValidator;
use Doctrine\ORM\EntityManagerInterface;


class BackpackManager extends AbstractManager
{
    /**
     * @var CurrentUser
     */
    private $currentUser;

    /**
     * @var BackpackHistory
     */
    private $backpackHistory;

    /**
     * @var BackpackRepository
     */
    private $backpackRepository;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackValidator $validator,
        CurrentUser $currentUser,
        BackpackHistory $backpackHistory,
        BackpackRepository $backpackRepository
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->backpackHistory = $backpackHistory;
        $this->backpackRepository = $backpackRepository;
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Backpack $bp
         */
        $bp = $entity;


        if (null === $entity->getId()) {
            $bp->setOwner($this->currentUser->getUser());
        } else {
            $bp->setUpdatedAt(new \DateTime());
        }

        if ($bp->getProcess() !== null) {
            $bp->setMProcess($bp->getProcess()->getMProcess());
        }

        if ($bp->getRef() === null && null !== $entity->getId()) {
            $ref = (new BackpackRefGenerator($this->backpackRepository, $bp))->get();
            $bp->setRef($ref);
        }
    }

    public function historize(Backpack $entity, ?Backpack $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->backpackHistory->setHistoryRelation($entity,'Porte-document');
            $this->backpackHistory->compare($entityOld, $entity);
        }
    }

    public function remove(EntityInterface $entity): void
    {
        /**
         * @var Backpack
         */
        $backpack =$entity;

        if ($backpack->getBackpackSlave()!==null) {

            $backpackSlave=$backpack->getBackpackSlave();
            $backpackSlave->setBackpackMaster(null);
            $this->save($backpackSlave);

            $backpack->setBackpackSlave(null);
            $this->save($backpack);

            $this->remove($backpackSlave);
        }

        
        $this->manager->remove($entity);
        $this->manager->flush();
    }

}
