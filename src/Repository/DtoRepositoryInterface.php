<?php

declare(strict_types=1);

namespace App\Repository;

use App\Dto\DtoInterface;

interface DtoRepositoryInterface
{
    public function findAllForDtoPaginator(DtoInterface $dto, $page = null, $limit = null);

    public function findAllForDto(DtoInterface $dto, string $filtre = '');

    public function countForDto(DtoInterface $dto);
}
