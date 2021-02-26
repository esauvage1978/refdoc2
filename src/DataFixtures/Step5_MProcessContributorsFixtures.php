<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\MProcess;
use App\Manager\MProcessManager;
use App\Repository\UserRepository;
use App\Validator\MProcessValidator;
use App\Repository\MProcessRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step5_MProcessContributorsFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var MProcessManager
     */
    private $mProcessManager;

    /**
     * @var MProcessValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var MProcessRepository
     */
    private $mProcessRepository;

    public function __construct(
        MProcessValidator $validator,
        MProcessManager $mProcessManager,
        MProcessRepository $mProcessRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->mProcessManager = $mProcessManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->userRepository=$userRepository;
        $this->mProcessRepository=$mProcessRepository;
    }

    private $data =
    [
        ['SAUVAGE Emmanuel', 'D3'],
        ['LIEKENS Ghislaine', ''],
        ['AMEDRO Jeremy', 'S5'],
        ['MIGNOT Bruno', ''],
        ['DESPALIER Margot', 'M3'],
        ['DUPLESSY Laurent', ''],
        ['ROSE Hélène', ''],
        ['BOISMARTEL Laurence', ''],
        ['PASZ Romarik', ''],
        ['CATELIN Isabelle', ''],
        ['BEAUCAMPS Sabine', ''],
        ['BUNIET Fanny', ''],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if($this->data[$i][1]!=='') {
                $user=$this->userRepository->findOneBy(['name'=> $this->data[$i][0]]);
                $refs=explode(';', $this->data[$i][1]);
                foreach($refs as $ref){
                    $mProcess = $this->mProcessRepository->findOneBy(['ref'=>$ref]);
                    $mProcess
                        ->addContributor($user);
                    $this->mProcessManager->initialise($mProcess);
                    $this->checkAndPersist($mProcess);
                }
            }
        }
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(MProcess $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }


    public static function getGroups(): array
    {
        return ['step5'];
    }
}
