<?php

namespace App\DataFixtures;

use App\Entity\Process;
use App\Entity\MProcess;
use App\Manager\MProcessManager;
use App\Repository\UserRepository;
use App\Validator\MProcessValidator;
use App\Repository\ProcessRepository;
use App\Repository\MProcessRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step6_ProcessFixtures extends Fixture implements FixtureGroupInterface
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
     * @var MProcessRepository
     */
    private $mProcessRepository;

    public function __construct(
        MProcessValidator $validator,
        MProcessManager $mProcessManager,
        MProcessRepository $mProcessRepository,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->mProcessManager = $mProcessManager;
        $this->entityManagerInterface = $entityManagerI;
        $this->mProcessRepository = $mProcessRepository;
    }

    private $data =
    [
        ['Accompagnement en santé', 'M2', 'M2-2', 'Définir et déployer des dispositifs d\'accompagnement'],
        ['Santé au travail', 'M2', 'M2-3', 'Définir et déployer des dispositifs d\'accompagnement'],
        ['Interventions du service social', 'M2', 'M2-2', 'Définir et déployer des dispositifs d\'accompagnement'],
        ['Accomagnement des offreurs de soins', 'M2', 'M2-2', 'Définir et déployer des dispositifs d\'accompagnement'],
        ['Lutte contre la fraude', 'M2', 'M2-3', ''],
        ['Gestion des bénéficiaires', 'M3', 'M3-1', 'Gérer le dossier client assuré'],
        ['CMU-C/ACS', 'M3', 'M3-3', 'Gérer le dossier client assuré'],
        ['Aide médicale d\'état', 'M3', 'M3-4', 'Gérer le dossier client assuré'],
        ['Reconnaissance AT-MP', 'M3', 'M3-5', 'Gérer le dossier client assuré'],
        ['Apporter une réponse à la demande du client et le conseiller le cas échéant sur nos offres de service', 'M1', 'M1-1', ''],
        ['Gestion de la relation clients (GRC) multisite', 'M1', '', ''],
        ['Conduite du dialogue social', 'S2', 'S2-1-RH-DS', ''],
        ['Gestion des emplois et des compétences', 'S2', 'S2-1-RH-GEC', ''],
        ['Gestion administrative du personnel', 'S2', 'S2-1-RH-GAP', ''],
        ['Gestion de la demande d\'évolution du système d\'information', 'S3', '', ''],
        ['Cadrage et définition de projet', 'S3', '', ''],
        ['Concéption détaillée et réalisation', 'S3', '', ''],
        ['Recette', 'S3', '', ''],
        ['Gestion les changements et les mise en production', 'S3', '', ''],
        ['Gestion des configurations', 'S3', '', ''],
        ['Traitements informatiques', 'S3', '', ''],
        ['Traitements éditiques', 'S3', '', ''],
        ['Gestion de la qualité de service', 'S3', '', ''],
        ['Gestion des incidents', 'S3', '', ''],
        ['Gestion des événements', 'S3', '', ''],
        ['Gestion des demandes de service', 'S3', '', ''],
        ['Gestion de l\'informatique locale', 'S3', '', ''],
        ['Trésorerie', 'S1', 'S1-1-ACF TRESO', ''],
        ['Gestion de la compatibilité', 'S1', 'S1-2-ACF TC', ''],
        ['Gestion des créances', 'S1', 'S1-3-ACF GCR', ''],
        ['Gestion des oppositions', 'S1', 'S1-4-ACF-GOP', ''],
        ['Recours contre tiers', 'M6', 'M6-1-RCT', ''],
        ['Contentieux technique maladie/AT', 'M6', 'M6-2-CXT', 'Gérer le pré-contentieux et le contentieux'],
        ['Contentieux technique tarification', 'M6', 'M6-2-CXT', 'Gérer le pré-contentieux et le contentieux'],
        ['Contentieux général', 'M6', 'M6-2-CXG', 'Gérer le pré-contentieux et le contentieux'],
        ['Clinique dentaire', 'M5', '', ''],
        ['Centre de santé', 'M5', '', ''],
        ['IJ maladie maternité paternité et adoption', 'M4', 'M4-8-IJ AS', 'Traiter les demandes de revenus de substitution'],
        ['IJ AT/MP', 'M4', 'M4-9-IJ AT/MP', 'Traiter les demandes de revenus de substitution'],
        ['Invalidité', 'M4', 'M4-10-INVA', 'Traiter les demandes de revenus de substitution'],
        ['Rentes AT/MP', 'M4', 'M4-11-RENTES AT/MP', 'Traiter les demandes de revenus de substitution'],
        ['Capital décés', 'M4', 'M4-12-CD', 'Traiter les demandes de revenus de substitution'],
        ['Allocation journalière d\'accompagnement des personnes en fin de vie', 'M4', 'M4-13-AJAP', 'Traiter les demandes de revenus de substitution'],
        ['Fonds de cessation anticipée d\'activité des travailleurs de l\'amiante', 'M4', 'M4-15-FCAATA', 'Traiter les demandes de revenus de substitution'],
        ['Feuille de soin électronique B2/SEFI', 'M4', 'M4-1-FSE B2 SEFI', 'Traiter les demandes de frais de santé'],
        ['Feuille de soin papier', 'M4', 'M4-2-FSP', 'Traiter les demandes de frais de santé'],
        ['Forfaits', 'M4', 'M4-3-FORFAITS', 'Traiter les demandes de frais de santé'],
        ['Etablissements privés', 'M4', 'M4-5-ETB PRIVES', 'Traiter les demandes de frais de santé'],
        ['Etablissements publics et ESMS', 'M4', 'M4-6-ETB PUBLICS/ESMS', 'Traiter les demandes de frais de santé'],
        ['Prestations d\'action sanitaire et social', 'M4', 'M4-14-ASS', 'Traiter les demandes de frais de santé'],
        ['Rémunération sur objectifs', 'M4', 'M4-4-REMUN OBJ', 'Traiter les demandes de frais de santé'],
        ['Gestion des fichiers établissements', 'M3', 'M3-8-FICHIERS ETB', 'Gérer le dossier client professionnels de santé'],
        ['Gestion des fichiers des OC', 'M3', 'M3-9-OC', 'Gérer le dossier client professionnels de santé'],
        ['Gestion du fichier PS', 'M3', 'M3-6-FICHIERS PS', 'Gérer le dossier client professionnels de santé'],
        ['', '', '', ''],
        ['', '', '', ''],
        ['', '', '', ''],
        ['', '', '', ''],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if ($this->data[$i][0] !== '') {
                $mProcess = $this->mProcessRepository->findOneBy(['ref' => $this->data[$i][1]]);
                $process = new Process();
                $process
                    ->setName($this->data[$i][0])
                    ->setRef($this->data[$i][2])
                    ->setGrouping($this->data[$i][3])
                    ->setMProcess($mProcess);
                $this->checkAndPersist($process);
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
        return ['step6'];
    }
}
