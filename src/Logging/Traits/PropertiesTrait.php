<?php

namespace Osm\Framework\Logging\Traits;

use Osm\Core\App;
use Osm\Framework\Logging\Logs;

trait PropertiesTrait
{
    public function Osm_Core_App__logs(App $app) {
        return Logs::new();
    }

}