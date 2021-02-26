<?php

namespace App\DataFixtures;

use App\Entity\MProcess;
use App\Manager\MProcessManager;
use App\Validator\MProcessValidator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step2_MProcessFixtures extends Fixture implements FixtureGroupInterface
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

    public function __construct(
        MProcessValidator $validator,
        MProcessManager $mProcessManager,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->mProcessManager = $mProcessManager;
        $this->entityManagerInterface = $entityManagerI;
    }

    private $data =
    [
        ['Définition de la stratégie','D1',true],
        ['Innovation et conception des produits et services', 'D2', true],
        ['Pilotage de la performance', 'D3', true],
        ['Contacts individuels', 'M1', true],
        ['Recours contre tiers et action pré-contentieuses et contentieuses', 'M6', true],
        ['Evolution des comportements/pratiques', 'M2', true],
        ['Dossier client', 'M3', true],
        ['Traitement des demandes de prestations', 'M4', true],
        ['Délivrances des soins', 'M5', true],
        ['Activités comptables et financières', 'S1', true],
        ['Ressources humaines', 'S2', true],
        ['Produits et services informatiques', 'S3', true],
        ['Documentation', 'S5', true],
        ['Achats', 'S6', true],
        ['Gestion immobilière', 'S7', true],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            $mProcess = new MProcess();
            $mProcess
                ->setName($this->data[$i][0])
                ->setRef($this->data[$i][1])
                ->setIsEnable($this->data[$i][2]);
            $this->mProcessManager->initialise($mProcess);
            $this->checkAndPersist($mProcess);
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
        return ['step2'];
    }
}
