<?php

namespace App\Manager;

use App\Entity\BackpackFileSource;

use App\Security\CurrentUser;
use App\Entity\EntityInterface;
use App\History\BackpackHistory;
use App\Manager\BackpackManager;
use App\Validator\BackpackFileSourceValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BackpackFileSourceRepository;


class BackpackFileSourceManager extends AbstractManager
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
     * @var BackpackFileSourceRepository
     */
    private $backpackFileSourceRepository;

    /**
     * @var BackpackManager
     */
    private $backpackManager;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackFileSourceValidator $validator,
        CurrentUser $currentUser,
        BackpackHistory $backpackHistory,
        BackpackFileSourceRepository $backpackFileSourceRepository,
        BackpackManager $backpackManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->backpackHistory = $backpackHistory;
        $this->backpackFileSourceRepository = $backpackFileSourceRepository;
        $this->backpackManager = $backpackManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());
    }

    public function historize(BackpackFileSource $entity, ?BackpackFileSource $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->backpackHistory->setHistoryRelation($entity->getBackpack(),'Fichier source');
            $this->backpackHistory->compareFile($entityOld, $entity);
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
}
