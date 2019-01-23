<?php

namespace Manadev\Framework\Db\Logging;

use Manadev\Framework\Data\CollectionRegistry;

class QueryLoggingClasses extends CollectionRegistry
{
    public $config = 'db_query_classes';

    protected function createItem($data, $name) {
        return $data;
    }
}