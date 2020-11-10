<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

use function array_merge;

trait TraitDtoRepository
{
    /** @var QueryBuilder */
    private $builder;

    /** @var array */
    private $params;

    private function addParams($key, $value): void
    {
        $onevalue = [$key => $value];
        if (empty($this->params)) {
            $this->params = $onevalue;
        } else {
            $this->params = array_merge($onevalue, $this->params);
        }
    }
}
