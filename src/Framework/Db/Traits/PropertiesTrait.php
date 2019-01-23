<?php

namespace Manadev\Framework\Db\Traits;

use Manadev\Core\App;
use Manadev\Framework\Db\Databases;

trait PropertiesTrait
{
    public function Manadev_Core_App__databases(App $app) {
        return $app->cache->remember("databases", function($data) {
            return Databases::new($data);
        });
    }

    public function Manadev_Core_App__db(App $app) {
        return $app->databases['main'];
    }

}