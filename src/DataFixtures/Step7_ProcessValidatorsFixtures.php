<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Process;
use App\Manager\ProcessManager;
use App\Repository\UserRepository;
use App\Validator\ProcessValidator;
use App\Repository\ProcessRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step7_ProcessValidatorsFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * @var ProcessValidator
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
        ProcessValidator $validator,
        ProcessManager $processManager,
        ProcessRepository $processRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->processManager = $processManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->userRepository=$userRepository;
        $this->processRepository=$processRepository;
    }

    private $data =
    [
        ['AMEDRO Jeremy', ''],
        ['BEAUCAMPS Sabine', ''],
        ['BOISMARTEL Laurence', 'S1-2-ACF-TC;S1-4-ACF-GOP'],
        ['BUNIET Fanny', ''],
        ['CATELIN Isabelle', ''],
        ['DESPALIER Margot', ''],
        ['DUPLESSY Laurent', ''],
        ['LIEKENS Ghislaine', ''],
        ['MARCQ David', 'M4-13-AJAP;M4-9-IJ-AT/MP;M4-8-IJ-AS'], 
        ['MIGNOT Bruno', 'M4-13-AJAP;M3-1;M4-9-IJ-AT/MP;M4-8-IJ-AS'],
        ['PASZ Romarik', 'M3-4;M4-12-CD;M3-3;M4-2-FSP;M4-1-FSE-B2-SEFI;S1-1-ACF-TRESO'],
        ['ROSE Hélène', 'S2-1-RH-DS;S2-1-RH-GEC'],
        ['SAUVAGE Emmanuel', ''],
        ['WILLEMS Gregory', 'M3-4;M4-12-CD;M3-3;M4-2-FSP;M4-1-FSE-B2-SEFI;S1-1-ACF-TRESO'],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if($this->data[$i][1]!=='') {
                $user=$this->userRepository->findOneBy(['name'=> $this->data[$i][0]]);
                $refs=explode(';', $this->data[$i][1]);
                foreach($refs as $ref){
                    $process = $this->processRepository->findOneBy(['ref'=>$ref]);
                    $process
                        ->addValidator($user);
                    $this->processManager->initialise($process);
                    $this->checkAndPersist($process);
                }
            }
        }
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(Process $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }


    public static function getGroups(): array
    {
        return ['step7'];
    }
}
