<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\EntityInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function count;

abstract class ValidatorAbstract implements LocalValidatorInterface
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var ConstraintViolationListInterface */
    private $errors;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function isValid(EntityInterface $entity): bool
    {
        $this->initialiseError($entity);

        return ! count($this->errors) ? true : false;
    }

    private function initialiseError(EntityInterface $entity): void
    {
        $this->errors = $this->validator->validate($entity);
    }

    public function getErrors(EntityInterface $entity): ?string
    {
        $this->initialiseError($entity);

        return (string) $this->errors;
    }
}
