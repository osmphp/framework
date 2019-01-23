<?php

namespace Manadev\Framework\Http\Traits;

use Manadev\Core\App;
use Manadev\Framework\Http\Query;
use Manadev\Framework\Http\Request;
use Manadev\Framework\Http\UrlGenerator;

trait PropertiesTrait
{
    public function Manadev_Core_App__request(App $app) {
        return Request::new();
    }

    public function Manadev_Core_App__url_generator(App $app) {
        return UrlGenerator::new();
    }

    public function Manadev_Core_App__query(App $app) {
        return Query::new();
    }

}