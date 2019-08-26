<?php

namespace Osm\Framework\Db\Logging;

use Osm\Framework\Data\CollectionRegistry;

class QueryLoggingClasses extends CollectionRegistry
{
    public $config = 'db_query_classes';

    protected function createItem($data, $name) {
        return $data;
    }
}