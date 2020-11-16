<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;
use App\Validator\SubscriptionValidator;
use Doctrine\ORM\EntityManagerInterface;

class SubscriptionManager extends AbstractManager
{
    public function __construct(EntityManagerInterface $manager, SubscriptionValidator $validator)
    {
        parent::__construct($manager, $validator);
    }

    public function initialise(EntityInterface $entity): void
    {
    }
}
