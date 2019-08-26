<?php

namespace Osm\Data\OptionLists\Traits;

use Osm\Core\App;
use Osm\Data\OptionLists\OptionLists;

trait PropertiesTrait
{
    public function Osm_Core_App__option_lists(App $app) {
        return $app->cache->remember("option_lists", function($data) {
            return OptionLists::new($data);
        });
    }
}