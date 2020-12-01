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
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Workflow\WorkflowBackpackManager;
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

            $nbr = $faker->numberBetween(0, 5);
            switch ($nbr) {
                case 1:
                    $transition = WorkflowData::TRANSITION_GO_ABANDONNED;
                    $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> resume', true);
                    break;
                case 2:
                    $transition = WorkflowData::TRANSITION_GO_ABANDONNED;
                    $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> abandonned', true);
                    $transition = WorkflowData::TRANSITION_GO_TO_RESUME;
                    $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> resume', true);
                    break;
                case 3:
                    $transition = WorkflowData::TRANSITION_GO_TO_VALIDATE;
                    $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> validate', true);
                    break;
                case 4:
                    if ($backpack->getCategory()->getIsValidatedByControl() === true) {
                        $transition = WorkflowData::TRANSITION_GO_TO_VALIDATE;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> validate', true);
                        $transition = WorkflowData::TRANSITION_GO_TO_CONTROL;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> to control', true);
                    }
                    break;
                case 5:
                    if ($backpack->getCategory()->getIsValidatedByDoc() === true && $backpack->getCategory()->getIsValidatedByControl() === false) {
                        $transition = WorkflowData::TRANSITION_GO_TO_VALIDATE;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> validate', true);
                        $transition = WorkflowData::TRANSITION_GO_TO_CHECK;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> to check', true);
                    } elseif ($backpack->getCategory()->getIsValidatedByDoc() === true && $backpack->getCategory()->getIsValidatedByControl() === true) {
                        $transition = WorkflowData::TRANSITION_GO_TO_VALIDATE;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> validate', true);
                        $transition = WorkflowData::TRANSITION_GO_TO_CONTROL;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> to control', true);
                        $transition = WorkflowData::TRANSITION_GO_TO_CHECK;
                        $this->workflow->applyTransition($backpack, $transition, 'Modification effectuée lors de la fixtures -> to check', true);
                    }
                    break;
            }

            //$this->checkAndPersist($backpack);
        }
        $this->entityManagerInterface->flush();
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
