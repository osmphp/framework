<?php

namespace Osm\Data\Indexing\Traits;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;

trait PropertiesTrait
{
    public function Osm_Framework_Migrations_Migration__indexing() {
        global $m_app; /* @var App $m_app */

        return $m_app->singleton(Indexing::class);
    }
}