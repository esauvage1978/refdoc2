<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\ProcessValidator;
use Doctrine\ORM\EntityManagerInterface;

class ProcessManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, ProcessValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {

    }
}
