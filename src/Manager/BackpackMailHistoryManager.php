<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\BackpackMailHistoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class BackpackMailHistoryManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, BackpackMailHistoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {

    }
}
