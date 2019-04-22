<?php

namespace Manadev\Data\Tables\Traits;

use Manadev\Core\App;
use Manadev\Data\Tables\Tables;
use Manadev\Framework\Db\Db;

trait PropertiesTrait
{
    public function Manadev_Framework_Db_Db__tables(Db $db) {
        global $m_app; /* @var App $m_app */

        /* @var Tables $result */
        $cacheKey = "tables.{$db->name}";
        $result = $m_app->cache->remember($cacheKey, function($data) {
            return Tables::new($data);
        });

        $result->parent = $db;
        return $result;
    }
}