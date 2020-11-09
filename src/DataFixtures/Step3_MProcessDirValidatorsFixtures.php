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

class Step3_MProcessDirValidatorsFixtures extends Fixture implements FixtureGroupInterface
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
        ['SAUVAGE Emmanuel', ''],
        ['LIEKENS Ghislaine', 'D1;D2;D3;M1;M2;M3;M4;S5'],
        ['AMEDRO Jeremy', ''],
        ['MIGNOT Bruno', ''],
        ['DESPALIER Margot', ''],
        ['DUPLESSY Laurent', 'M5;M4'],
        ['ROSE Hélène', 'S2;S3;S6'],
        ['BOISMARTEL Laurence', 'S1'],
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
                        ->addDirValidator($user);
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
        return ['step3'];
    }
}
