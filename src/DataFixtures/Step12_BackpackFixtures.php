<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\Backpack;
use App\Entity\BackpackLink;
use App\Manager\BackpackManager;
use App\Repository\UserRepository;
use App\Validator\BackpackValidator;
use App\Repository\ProcessRepository;
use App\Repository\CategoryRepository;
use App\Repository\MProcessRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step12_BackpackFixtures extends Fixture implements FixtureGroupInterface
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

    private $repertoire=[
        'Répertoire 1',
        'description',
        'activité 1',
        'activité 2',
        'activité 3',
        'activité 4',
        'activité 5',
        'activité 6',
    ];

    private $users;
    private $mprocesses;
    private $processes;
    private $categories;

    public function __construct(
        BackpackValidator $validator,
        BackpackManager $backpackManager,
        ProcessRepository $processRepository,
        MProcessRepository $mProcessRepository,
        UserRepository $userRepository,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->backpackManager = $backpackManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->userRepository = $userRepository;
        $this->users = $userRepository->findAll();
        $this->mprocesses = $mProcessRepository->findAll();
        $this->processes = $processRepository->findAll();
        $this->categories = $categoryRepository->findAll();
    }


    public function load(ObjectManager $manager)
    {


        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 1000; $i++) {

            $backpackLink = new BackpackLink();
            $backpackLink
                ->setTitle('Link add ' . $i)
                ->setContent('Auto add in fixtures')
                ->setLink('Http://google.fr');

            $backpack = new Backpack();
            $user=$this->users[$faker->numberBetween(0, count($this->users)-1)];
            $backpack
                ->setOwner($user)
                ->setCategory($this->categories[$faker->numberBetween(0, count($this->categories) - 1)])
                ->setContent($faker->realText(500))
                ->setName('fxt' . $faker->realText(40))
                ->setCreatedAt($faker->dateTimeBetween($startDate = '-2 years', $endDate = 'now', $timezone = null))
                ->addBackpackLink($backpackLink);

            if ($faker->numberBetween(0, 1) == 1) {
                $backpack->setMProcess($this->mprocesses[$faker->numberBetween(0, count($this->mprocesses) - 1)]);
            } else {
                $process= $this->processes[$faker->numberBetween(0, count($this->processes) - 1)];
                $backpack->setProcess($process);
                $backpack->setMProcess($process->getMProcess());
            }

            if ($faker->numberBetween(0, 1) == 1) {
                $backpack->setDir1($this->repertoire[$faker->numberBetween(0, count($this->repertoire) - 1)]);
                if ($faker->numberBetween(0, 1) == 1) {
                    $backpack->setDir2($this->repertoire[$faker->numberBetween(0, count($this->repertoire) - 1)]);
                    if ($faker->numberBetween(0, 1) == 1) {
                        $backpack->setDir3($this->repertoire[$faker->numberBetween(0, count($this->repertoire) - 1)]);
                    }
                }
            }

            $this->checkAndPersist($backpack);



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
        return ['step12'];
    }
}
