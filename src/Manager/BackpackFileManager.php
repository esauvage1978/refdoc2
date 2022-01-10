<?php

namespace App\Manager;

use App\Entity\BackpackFile;

use App\Security\CurrentUser;
use App\Helper\ContentChecker;
use App\Entity\EntityInterface;
use App\History\BackpackHistory;
use App\Manager\BackpackManager;
use App\Validator\BackpackFileValidator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BackpackFileRepository;


class BackpackFileManager extends AbstractManager
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
     * @var BackpackFileRepository
     */
    private $backpackFileRepository;

    /**
     * @var BackpackManager
     */
    private $backpackManager;

    public function __construct(
        EntityManagerInterface $manager,
        BackpackFileValidator $validator,
        CurrentUser $currentUser,
        BackpackHistory $backpackHistory,
        BackpackFileRepository $backpackFileRepository,
        BackpackManager $backpackManager
    ) {
        parent::__construct($manager, $validator);
        $this->currentUser = $currentUser;
        $this->backpackHistory = $backpackHistory;
        $this->backpackFileRepository = $backpackFileRepository;
        $this->backpackManager = $backpackManager;
    }

    public function initialise(EntityInterface $entity): void
    {
        $entity->setModifyAt(new \DateTime());

        
        $entity->setContent( ContentChecker::run($entity->getContent()) );
    }

    public function historize(BackpackFile $entity, ?BackpackFile $entityOld = null)
    {
        if (null !== $entityOld) {
            $this->backpackHistory->setHistoryRelation($entity->getBackpack(),'Fichier');
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

    public function remove(EntityInterface $entity): void
    {
        $this->backpackHistory->setHistoryRelation($entity->getBackpack(),'Fichier');
        $this->backpackHistory->compareFile( $entity,null);

        $this->manager->remove($entity);
        $this->manager->flush();
    }
}
