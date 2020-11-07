<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use App\Validator\UserValidator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    public function __construct(
        UserValidator $validator,
        UserManager $userManager,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->userManager = $userManager;
        $this->entityManagerInterface = $entityManagerI;
    }

    private $data =
    [
        ['SAUVAGE Emmanuel','emmanuel.sauvage@live.fr','Fckgwrhqq101',true,true,true, ["ROLE_USER", "ROLE_ADMIN"]],
        ['LIEKENS Ghislaine','ghislaine.liekens@assurance-maladie.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_USER"]],
        ['AMEDRO Jeremy', 'jeremy.amedro@assurance-maladie.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_GESTIONNAIRE"]],
        ['MIGNOT Bruno', 'bruno.mignot@assurance-maladie.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_GESTIONNAIRE"]],
        ['DESPALIER Margot', 'margot.despalier@assurance-maladie.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_USER"]],
        ['DUPLESSY Laurent', 'laurent.duplessy@assurance-maladie.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_USER"]],
        ['ROSE Hélène', 'helene.rose@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],
        ['BOISMARTEL Laurence', 'laurence.boismartel@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],
        ['PASZ Romarik', 'romarik.pasz@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],
        ['CATELIN Isabelle', 'isabelle.catelin@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],
        ['BEAUCAMPS Sabine', 'sabine.beaucamps@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],
        ['BUNIET Fanny', 'fanny.bunniet@assurance-maladie.fr', 'Fckgwrhqq101', true, true,true, ["ROLE_USER"]],

    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            $user = new User();
            $user
                ->setName($this->data[$i][0])
                ->setEmail($this->data[$i][1])
                ->setPlainPassword($this->data[$i][2])
                ->setEmailValidated($this->data[$i][3])
                ->setIsEnable($this->data[$i][4])
                ->setRoles($this->data[$i][6]);
            $this->userManager->initialise($user);
            $this->checkAndPersist($user);
        }
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(User $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }
}