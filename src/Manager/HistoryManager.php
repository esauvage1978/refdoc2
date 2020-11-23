<?php

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\HistoryValidator;
use Doctrine\ORM\EntityManagerInterface;

class HistoryManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, HistoryValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        // TODO: Implement initialise() method.
    }
}
