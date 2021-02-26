<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Entity\User;
use App\Security\CurrentUser;
use App\Validator\CategoryValidator;
use App\Workflow\WorkflowData;
use App\Workflow\WorkflowNames;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager extends AbstractManager
{
    public function __construct(
        EntityManagerInterface $manager,
        CategoryValidator $validator
    ) {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        /**
         * @var Category
         */
        $cat=$entity;


        if($cat->getIsValidatedByDoc() && $cat->getIsValidatedByControl()) {
            $cat->setWorkflowName(WorkflowNames::WORKFLOW_ALL);
        } else if(!$cat->getIsValidatedByDoc() && !$cat->getIsValidatedByControl()) {
            $cat->setWorkflowName(WorkflowNames::WORKFLOW_WITHOUT_CONTROL);
        } else if (!$cat->getIsValidatedByDoc() && $cat->getIsValidatedByControl()) {
            $cat->setWorkflowName(WorkflowNames::WORKFLOW_WITHOUT_DOC);
        } else  {
            $cat->setWorkflowName(WorkflowNames::WORKFLOW_WITHOUT_DOCCONTROL);
        }

    }
}
