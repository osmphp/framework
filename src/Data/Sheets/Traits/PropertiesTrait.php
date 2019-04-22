<?php

namespace Manadev\Data\Sheets\Traits;

use Manadev\Core\App;
use Manadev\Data\Sheets\Sheets;

trait PropertiesTrait
{
    public function Manadev_Core_App__sheets(App $app) {
        return $app->cache->remember("sheets", function($data) {
            return Sheets::new($data);
        });
    }
}