<?php

namespace App\Manager;

use App\Entity\User;
use App\Entity\Backpack;
use App\Entity\BackpackFile;
use App\Entity\BackpackLink;
use App\Entity\BackpackState;
use App\Workflow\WorkflowData;
use App\Entity\EntityInterface;
use App\Helper\FileTools;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Validator\BackpackStateValidator;

class BackpackDuplicatorManager extends AbstractManager
{
    /**
     * @var BackpackStateManager
     */
    private $backpackStateManager;

    /**
     * @var BackpackManager
     */
    private $backpackManager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(
        BackpackStateManager $backpackStateManager,
        EntityManagerInterface $manager,
        BackpackManager $backpackManager,
        BackpackStateValidator $validator,
        UserRepository $userRepository,
        FileTools $fileTools
    ) {
        parent::__construct($manager, $validator);
        $this->userRepository = $userRepository;
        $this->backpackStateManager = $backpackStateManager;
        $this->backpackManager = $backpackManager;
    }

    public function duplicate(Backpack $item, User $user)
    {
        $itemDupliqued = $this->duplicateBackpack($item);

        $this->setMasterSlave($item, $itemDupliqued);

        $this->duplicateLink($item, $itemDupliqued);
        $this->duplicateFile($item, $itemDupliqued);

        $this->goToResume($item);
        $this->goInReview($itemDupliqued);

        $this->backpackStateManager->saveActionInHistory($item, WorkflowData::STATE_IN_REVIEW, $user);
    }

    public function initialise(EntityInterface $entity): void
    {
    }

    private function duplicateBackpack(Backpack $item): Backpack
    {
        $duplicate = new Backpack();
        $duplicate
            ->setName($item->getName())
            ->setRef($item->getRef())
            ->setCreatedAt($item->getCreatedAt())
            ->setOwner($item->getOwner())
            ->setProcess($item->getProcess())
            ->setMProcess($item->getMProcess())
            ->setCategory($item->getCategory())
            ->setContent($item->getContent())
            ->setDir1($item->getDir1())
            ->setDir2($item->getDir2())
            ->setDir3($item->getDir3())
            ->setDir4($item->getDir4())
            ->setDir5($item->getDir5());

        return $duplicate;
    }

    private function setMasterSlave(Backpack $item, Backpack $itemDupliqued)
    {
        $itemDupliqued->setBackpackMaster($item);
        $item->setBackpackSlave($itemDupliqued);

        $this->backpackManager->save($itemDupliqued);
        $this->backpackManager->save($item);
    }

    private function duplicateLink(Backpack $item, Backpack $itemDupliqued)
    {
        foreach ($item->getBackpackLinks() as $link) {
            $linkDuplicated = new BackpackLink();
            $linkDuplicated
                ->setTitle($link->getTitle())
                ->setContent($link->getContent())
                ->setLink($link->getLink());

            $itemDupliqued->addBackpackLink($linkDuplicated);
            $this->backpackManager->save($itemDupliqued);
        }
    }

    private function duplicateFile(Backpack $item, Backpack $itemDupliqued)
    {
        foreach ($item->getBackpackFiles() as $file) {
            $fileDuplicated = new BackpackFile();
            $fileDuplicated
                ->setTitle($file->getTitle())
                ->setContent($file->getContent())
                ->setFileExtension($file->getFileExtension())
                ->setFileName($file->getFileName().'_copy');

            $itemDupliqued->addBackpackFile($fileDuplicated);
            $this->backpackManager->save($itemDupliqued);
        }
    }

    private function goToResume(Backpack $item)
    {
        $item
            ->setStateAt(new \DateTime())
            ->setStateContent('Duplication automatique effectuée du porte-document')
            ->setStateCurrent(WorkflowData::STATE_TO_RESUME);
        $this->backpackManager->save($item);
    }

    private function goInReview (Backpack $item)
    {
        $item
            ->setStateAt(new \DateTime())
            ->setStateContent('Duplication automatique effectuée du porte-document')
            ->setStateCurrent(WorkflowData::STATE_IN_REVIEW);
        $this->backpackManager->save($item);
    }
}
