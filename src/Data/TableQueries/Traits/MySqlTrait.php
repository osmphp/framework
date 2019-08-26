<?php

namespace Osm\Data\TableQueries\Traits;

use Osm\Data\TableQueries\MySqlTableQuery;
use Osm\Framework\Db\Db;

trait MySqlTrait
{
    public function offsetGet($table) {
        $db = $this; /* @var Db $db */
        return MySqlTableQuery::new([], null)->db($db)->from($table);
    }
}