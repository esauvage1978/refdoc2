<?php

namespace App\History;

use App\Entity\EntityInterface;


interface HistoryEntityInterface
{
    public function compare(EntityInterface $itemOld, EntityInterface $itemNew);

}
