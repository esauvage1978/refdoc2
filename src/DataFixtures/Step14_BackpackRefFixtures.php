<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Backpack;
use App\Workflow\WorkflowData;
use App\Manager\BackpackManager;
use App\Repository\UserRepository;
use App\Validator\BackpackValidator;
use App\Repository\ProcessRepository;
use App\Repository\BackpackRepository;
use App\Repository\CategoryRepository;
use App\Repository\MProcessRepository;
use App\Service\BackpackRefGenerator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Workflow\WorkflowBackpackManager;
use App\Workflow\WorkflowNames;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step14_BackpackRefFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var BackpackManager
     */
    private $backpackManager;


    /**
     * @var backpackRepository
     */
    private $backpackRepository;

    /**
     * @var BackpackValidator
     */
    private $validator;


    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var WorkflowBackpackManager
     */
    private $workflow;

    private $backpacks;

    public function __construct(
        BackpackValidator $validator,
        BackpackManager $backpackManager,
        BackpackRepository $backpackRepository,
        EntityManagerInterface $entityManagerI,
        WorkflowBackpackManager $workflow
    ) {
        $this->validator = $validator;
        $this->backpackManager = $backpackManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->backpacks = $backpackRepository->findAll();
        $this->workflow = $workflow;
        $this->backpackRepository= $backpackRepository;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < count($this->backpacks); $i++) {

            /**
             * var Backpack
             */
            $backpack = $this->backpacks[$i];
            if($backpack->getStateCurrent()!==WorkflowData::STATE_ABANDONNED) {
                $bgr=new BackpackRefGenerator($this->backpackRepository,$backpack);
                $backpack
                    ->setRef($bgr->get());
                $this->checkAndPersist($backpack);
                $this->entityManagerInterface->flush();
            }
        }
    }

    private function checkAndPersist(Backpack $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }


    public static function getGroups(): array
    {
        return ['step14'];
    }
}
