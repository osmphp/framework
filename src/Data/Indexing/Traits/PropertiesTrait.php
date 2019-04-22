<?php

namespace Manadev\Data\Indexing\Traits;

use Manadev\Core\App;
use Manadev\Data\Indexing\Indexing;

trait PropertiesTrait
{
    public function Manadev_Framework_Migrations_Migration__indexing() {
        global $m_app; /* @var App $m_app */

        return $m_app->singleton(Indexing::class);
    }
}