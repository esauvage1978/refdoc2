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

class Step13_BackpackStateFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var BackpackManager
     */
    private $backpackManager;


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
    }


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < count($this->backpacks); $i++) {

            /**
             * var Backpack
             */
            $backpack = $this->backpacks[$i];

            $nbr = $faker->numberBetween(0, 6);
            switch ($nbr) {
                case 1:
                    $backpack->setStateCurrent(WorkflowData::STATE_ABANDONNED);
                    break;
                case 2:
                    $backpack->setStateCurrent(WorkflowData::STATE_TO_RESUME);
                    break;
                case 3:
                    $backpack->setStateCurrent(WorkflowData::STATE_TO_VALIDATE);
                    break;
                case 4:
                    if (
                        $backpack->getCategory()->getWorkflowName() === WorkflowNames::WORKFLOW_ALL ||
                        $backpack->getCategory()->getWorkflowName() === WorkflowNames::WORKFLOW_WITHOUT_DOC
                    ) {
                        $backpack->setStateCurrent(WorkflowData::STATE_TO_CONTROL);
                    }
                    break;
                case 5:
                    if (
                        $backpack->getCategory()->getWorkflowName() === WorkflowNames::WORKFLOW_WITHOUT_CONTROL ||
                        $backpack->getCategory()->getWorkflowName() === WorkflowNames::WORKFLOW_ALL
                    ) {
                        $backpack->setStateCurrent(WorkflowData::STATE_TO_CHECK);
                    }
                    break;
                case 6:
                    $backpack->setStateCurrent(WorkflowData::STATE_PUBLISHED);
                    break;
            }
            $backpack
                ->setStateContent('Forçage de l\'état dans les fixtures sans passer par le cycle normal')
                ->setStateAt($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null))
                ->setUpdatedAt($faker->dateTimeBetween($startDate = '-1 years', $endDate = 'now', $timezone = null));
            $this->checkAndPersist($backpack);
            $this->entityManagerInterface->flush();
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
        return ['step13'];
    }
}
