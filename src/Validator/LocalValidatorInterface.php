<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

interface LocalValidatorInterface
{
    public function __construct(ValidatorInterface $validator);

    public function isValid(EntityInterface $entity): bool;

    public function getErrors(EntityInterface $entity): ?string;
}
