<?php

namespace App\Manager;

use App\Entity\BackpackLink;

use App\Security\CurrentUser;
use App\Entity\EntityInterface;
use App\History\BackpackHistory;
use App\Validator\BackpackLinkValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BackpackLinkRepository;


class BackpackLinkManager extends AbstractManager
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
     * @var BackpackLinkRepository
     */
    private $backpackLinkRepository;


    /**
     * @var BackpackManager
     */
    private $backpackManager;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackLinkValidator $validator,
        CurrentUser $currentUser,
        BackpackHistory $backpackHistory,
        BackpackLinkRepository $backpackLinkRepository,
        BackpackManager $backpackManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->backpackHistory = $backpackHistory;
        $this->backpackLinkRepository = $backpackLinkRepository;
        $this->backpackManager = $backpackManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());
    }

    public function historize(BackpackLink $entity, ?BackpackLink $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->backpackHistory->setHistoryRelation($entity->getBackpack(),'Lien');
            $this->backpackHistory->compareLink($entityOld, $entity);
        }
    }

    public function save(EntityInterface $entity): bool
    {
        $this->initialise($entity);

        if (!$this->validator->isValid($entity)) {
            return false;
        }

        $this->manager->persist($entity);
        $this->manager->flush();

        $this->backpackManager->save($entity->getBackpack());

        return true;
    }

    public function remove(EntityInterface $entity): void
    {
        $this->backpackHistory->setHistoryRelation($entity->getBackpack(),'Lien');
        $this->backpackHistory->compareLink( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
