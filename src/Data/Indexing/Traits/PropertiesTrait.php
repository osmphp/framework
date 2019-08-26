<?php

namespace Osm\Data\Indexing\Traits;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;

trait PropertiesTrait
{
    public function Osm_Framework_Migrations_Migration__indexing() {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->singleton(Indexing::class);
    }
}