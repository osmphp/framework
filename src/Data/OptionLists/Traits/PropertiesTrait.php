<?php

namespace Manadev\Data\OptionLists\Traits;

use Manadev\Core\App;
use Manadev\Data\OptionLists\OptionLists;

trait PropertiesTrait
{
    public function Manadev_Core_App__option_lists(App $app) {
        return $app->cache->remember("option_lists", function($data) {
            return OptionLists::new($data);
        });
    }
}