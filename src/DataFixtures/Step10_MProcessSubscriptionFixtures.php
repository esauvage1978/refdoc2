<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Process;
use App\Entity\Subscription;
use App\Manager\ProcessManager;
use App\Repository\UserRepository;
use App\Validator\ProcessValidator;
use App\Manager\SubscriptionManager;
use App\Repository\ProcessRepository;
use App\Repository\MProcessRepository;
use Doctrine\Persistence\ObjectManager;
use App\Validator\SubscriptionValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step10_MProcessSubscriptionFixtures extends Fixture implements FixtureGroupInterface
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
     * @var MProcessRepository
     */
    private $mProcessRepository;

    public function __construct(
        SubscriptionValidator $validator,
        SubscriptionManager $subscriptionManager,
        MProcessRepository $mprocessRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->subscriptionManager = $subscriptionManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->userRepository=$userRepository;
        $this->mprocessRepository=$mprocessRepository;
    }

    private $data =
    [
        ['AMEDRO Jeremy', 'S5'],
        ['BEAUCAMPS Sabine', 'M1'],
        ['BOISMARTEL Laurence', ''],
        ['BUNIET Fanny', ''],
        ['CATELIN Isabelle', ''],
        ['DESPALIER Margot', 'M3'],
        ['DUPLESSY Laurent', 'M2;M6'],
        ['LIEKENS Ghislaine', 'D1;D2;D3'],
        ['MARCQ David', 'M3'], 
        ['MIGNOT Bruno', 'M3'],
        ['PASZ Romarik', 'S1'],
        ['ROSE Hélène', 'S6;S7;S3'],
        ['SAUVAGE Emmanuel', 'S3;S5;S2;D1'],
        ['WILLEMS Gregory', 'M5;M6;M1;M2;M3'],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if($this->data[$i][1]!=='') {
                $user=$this->userRepository->findOneBy(['name'=> $this->data[$i][0]]);
                $refs=explode(';', $this->data[$i][1]);
                foreach($refs as $ref){
                    $mprocess = $this->mprocessRepository->findOneBy(['ref'=>$ref]);
                    $subscription=new Subscription();
                    $subscription
                        ->setUser($user)
                        ->setMProcess($mprocess);
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
        return ['step10'];
    }
}
