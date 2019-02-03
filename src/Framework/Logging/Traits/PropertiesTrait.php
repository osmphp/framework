<?php

namespace Manadev\Framework\Logging\Traits;

use Manadev\Core\App;
use Manadev\Framework\Logging\Logs;

trait PropertiesTrait
{
    public function Manadev_Core_App__logs(App $app) {
        return Logs::new();
    }

}