<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Process;
use App\Entity\Subscription;
use App\Repository\UserRepository;
use App\Manager\SubscriptionManager;
use App\Repository\ProcessRepository;
use Doctrine\Persistence\ObjectManager;
use App\Validator\SubscriptionValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step9_ProcessSubscriptionFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var SubscriptionManager
     */
    private $subscriptionManager;

    /**
     * @var SubscriptionValidator
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
     * @var ProcessRepository
     */
    private $processRepository;

    public function __construct(
        SubscriptionValidator $validator,
        SubscriptionManager $subscriptionManager,
        ProcessRepository $processRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->subscriptionManager = $subscriptionManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->userRepository=$userRepository;
        $this->processRepository=$processRepository;
    }

    private $data =
    [
        ['AMEDRO Jeremy', 'M2-2;M3-4'],
        ['BEAUCAMPS Sabine', 'M4-3-FORFAITS'],
        ['BOISMARTEL Laurence', 'M4-15-FCAATA;M3-8-FICHIERS-ETB'],
        ['BUNIET Fanny', 'M3-4;S1-3-ACF-GCR'],
        ['CATELIN Isabelle', 'M4-15-FCAATA;S1-3-ACF-GCR'],
        ['DESPALIER Margot', 'M4-13-AJAP;M4-9-IJ-AT/MP;M4-8-IJ AS;M3-8-FICHIERS-ETB'],
        ['DUPLESSY Laurent', 'M4-2-FSP'],
        ['LIEKENS Ghislaine', 'M2-2;S1-2-ACF-TC'],
        ['MARCQ David', 'M4-2-FSP;S1-2-ACF-TC'], 
        ['MIGNOT Bruno', 'S2-1-RH-DS;S1-4-ACF-GOP'],
        ['PASZ Romarik', 'M3-3;S1-4-ACF-GOP;M4-8-IJ-AS'],
        ['ROSE Hélène', 'M4-12-CD;M3-6-FICHIERS-PS'],
        ['SAUVAGE Emmanuel', 'M4-13-AJAP;S1-2-ACF-TC;S1-4-ACF-GOP;S2-1-RH-DS;M4-10-INVA'],
        ['WILLEMS Gregory', 'M4-3-FORFAITS'],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if($this->data[$i][1]!=='') {
                $user=$this->userRepository->findOneBy(['name'=> $this->data[$i][0]]);
                $refs=explode(';', $this->data[$i][1]);
                foreach($refs as $ref){
                    $process = $this->processRepository->findOneBy(['ref'=>$ref]);
                    $subscription=new Subscription();
                    $subscription
                        ->setUser($user)
                        ->setProcess($process);
                    $this->subscriptionManager->initialise($subscription);
                    $this->checkAndPersist($subscription);
                }
            }
        }
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(Subscription $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) );
    }


    public static function getGroups(): array
    {
        return ['step9'];
    }
}
