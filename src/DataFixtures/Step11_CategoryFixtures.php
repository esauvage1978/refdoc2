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
        ['Consigne', '<p>Les consignes sont des données <b>éphémères </b>et <b>succinctes</b>. </p><p>Une consigne <b>pérenne </b>doit être insérer dans un mode opératoire.<br></p>',false, false,12, 'fas fa-clipboard-check', '#ffffff', '#ff00ff', WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL],
        ['Consigne validée par le service contrôle', '<p>Les consignes sont des données <b>éphémères </b>et <b>succinctes</b>. </p><p>Une consigne <b>pérenne </b>doit être insérer dans un mode opératoire.<br></p>', true, false, 12, 'fas fa-clipboard-check', '#ffffff', '#ff00ff', WorkflowNames::WORKFLOW_WITHOUT_DOC],
        ['Mode opératoire', '', false, true, 12, 'fas fa-clipboard-list', '#ffffff', '#ff8000', WorkflowNames::WORKFLOW_WITHOUT_CONTROL],
        ['Mode opératoire validé par le service contrôle', '', true, true, 12, 'fas fa-clipboard-list', '#ffffff', '#ff8000', WorkflowNames::WORKFLOW_ALL],
        ['Procédure stratégique', '<p>Définition de la note stratégique à définir ici<br></p>', false, true, 12, 'fas fa-journal-whills', '#ffffff', '#0080ff', WorkflowNames::WORKFLOW_WITHOUT_CONTROL],
        ['Note de procédure', '', false, true, 12, 'fas fa-book', '#ffffff', '#00bf00', WorkflowNames::WORKFLOW_WITHOUT_CONTROL],
    ];

    public function load(ObjectManager $manager)
    {

        for ($i = 0; $i < count($this->data); $i++) {
            if ($this->data[$i][0] !== '') {
                $entity = new Category();
                $entity
                    ->setName($this->data[$i][0])
                    ->setContent($this->data[$i][1])
                    ->setIsValidatedByControl($this->data[$i][2])
                    ->setIsValidatedByDoc($this->data[$i][3])
                    ->setTimeBeforeRevision($this->data[$i][4])
                    ->setIcone($this->data[$i][5])
                    ->setBgColor($this->data[$i][6])
                    ->setForeColor($this->data[$i][7])
                    ->setWorkflowName($this->data[$i][8]);

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
