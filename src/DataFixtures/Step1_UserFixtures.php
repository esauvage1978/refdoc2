<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Manager\UserManager;
use App\Validator\UserValidator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step1_UserFixtures extends Fixture implements FixtureGroupInterface
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
        ['AMEDRO Jeremy', 'contact@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_GESTIONNAIRE"]],
        ['BEAUCAMPS Sabine', 'esauvage1978@gmail.com', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['BOISMARTEL Laurence', 'amz_kergava@outlook.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['BUNIET Fanny', 'kergava_amz@outlook.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['CATELIN Isabelle', 'contact@robot-educatif.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['DESPALIER Margot', 'contact@par.manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['DUPLESSY Laurent', 'contact2@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['LIEKENS Ghislaine', 'admin@avocat-parafiniuk.com', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['MARCQ David', 'contact3@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['MIGNOT Bruno', 'contact4@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_GESTIONNAIRE"]],
        ['PASZ Romarik', 'contact5@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['ROSE Hélène', 'contact6@manuso.fr', 'Fckgwrhqq101', true, true, false, ["ROLE_USER"]],
        ['SAUVAGE Emmanuel', 'emmanuel.sauvage@live.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_USER", "ROLE_ADMIN"]],
        ['WILLEMS Gregory', 'contact7@manuso.fr', 'Fckgwrhqq101', true, true, true, ["ROLE_USER", "ROLE_ADMIN"]],
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
                ->setEmailValidated($this->data[$i][5])
                ->setRoles($this->data[$i][6]);

            if ($this->data[$i][5]) {
                $user->setEmailValidatedToken(date_format(new \DateTime(), 'Y-m-d H:i:s'));
            }
            
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

    public static function getGroups(): array
    {
        return ['step1'];
    }
}
