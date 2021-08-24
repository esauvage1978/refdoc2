<?php

namespace App\History;


abstract class HistoryTypeOfCompare
{
    const FIELD = 'field';
    const RELATION_ONE_TO_ONE = 'one_to_one';
    const RELATION_ONE_TO_MANY = 'one_to_many';
    const ADD_OR_DELETE = 'add_or_delete';
}
