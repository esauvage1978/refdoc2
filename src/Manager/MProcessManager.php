<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\MProcessValidator;
use Doctrine\ORM\EntityManagerInterface;

class MProcessManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, MProcessValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
        if ($entity->getRef()==null) {
            $entity->setRef('000');
        }
    }
}
