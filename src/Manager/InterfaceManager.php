<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\EntityInterface;

interface InterfaceManager
{
    public function initialise(EntityInterface $entity): void;

    public function save(EntityInterface $entity): bool;

    public function getErrors(EntityInterface $entity): ?string;

    public function remove(EntityInterface $entity): void;
}
