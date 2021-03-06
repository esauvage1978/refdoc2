<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Manager\CategoryManager;
use App\Validator\CategoryValidator;
use App\Repository\CategoryRepository;
use App\Workflow\WorkflowNames;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class Step11_CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * @var CategoryManager
     */
    private $categoryManager;

    /**
     * @var CategoryValidator
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;



    public function __construct(
        CategoryValidator $validator,
        CategoryManager $categoryManager,
        EntityManagerInterface $entityManagerI
    ) {
        $this->validator = $validator;
        $this->categoryManager = $categoryManager;
        $this->entityManagerInterface = $entityManagerI;
    }

    private $data =
    [
        ['Consigne','CO', '<p>Les consignes sont des données <b>éphémères </b>et <b>succinctes</b>. </p><p>Une consigne <b>pérenne </b>doit être insérer dans un mode opératoire.<br></p>',false, false,12, 'fas fa-clipboard-check', '#ffffff', '#8e4d97', WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL,false],
        ['Consigne validée','CO', '<p>Les consignes sont des données <b>éphémères </b>et <b>succinctes</b>. </p><p>Une consigne <b>pérenne </b>doit être insérer dans un mode opératoire.<br></p>', true, false, 12, 'fas fa-clipboard-check', '#ffffff', '#8e4d97', WorkflowNames::WORKFLOW_WITHOUT_DOC,false],
        ['Mode opératoire','MO', '', false, true, 12, 'fas fa-clipboard-list', '#ffffff', '#e28e24', WorkflowNames::WORKFLOW_WITHOUT_CONTROL,false],
        ['Mode opératoire validé','MO', '', true, true, 12, 'fas fa-clipboard-list', '#ffffff', '#e28e24', WorkflowNames::WORKFLOW_ALL,false],
        ['Procédure stratégique','PS', '<p>Définition de la note stratégique à définir ici<br></p>', false, true, 12, 'fas fa-journal-whills', '#ffffff', '#039be5', WorkflowNames::WORKFLOW_WITHOUT_CONTROL,true],
        ['Note de procédure','NP', '', false, true, 12, 'fas fa-book', '#ffffff', '#35b124', WorkflowNames::WORKFLOW_WITHOUT_CONTROL,false],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if ($this->data[$i][0] !== '') {
                $entity = new Category();
                $entity
                    ->setName($this->data[$i][0])
                    ->setRef($this->data[$i][1])
                    ->setContent($this->data[$i][2])
                    ->setIsValidatedByControl($this->data[$i][3])
                    ->setIsValidatedByDoc($this->data[$i][4])
                    ->setTimeBeforeRevision($this->data[$i][5])
                    ->setIcone($this->data[$i][6])
                    ->setBgColor($this->data[$i][7])
                    ->setForeColor($this->data[$i][8])
                    ->setWorkflowName($this->data[$i][9])
                    ->setIsValidatedByADD($this->data[$i][10]);

                $this->checkAndPersist($entity);
            }
        }
        $this->entityManagerInterface->flush();
    }

    private function checkAndPersist(Category $instance)
    {
        if ($this->validator->isValid($instance)) {
            $this->entityManagerInterface->persist($instance);

            return;
        }
        var_dump('Validator : ' . $this->validator->getErrors($instance) . $instance->getName());
    }


    public static function getGroups(): array
    {
        return ['step11'];
    }
}
