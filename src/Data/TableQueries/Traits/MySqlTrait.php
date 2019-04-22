<?php

namespace Manadev\Data\TableQueries\Traits;

use Manadev\Data\TableQueries\MySqlTableQuery;
use Manadev\Framework\Db\Db;

trait MySqlTrait
{
    public function offsetGet($table) {
        $db = $this; /* @var Db $db */
        return MySqlTableQuery::new([], null)->db($db)->from($table);
    }
}