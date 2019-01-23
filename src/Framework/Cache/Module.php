<?php

namespace Manadev\Framework\Cache;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;

class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    public function terminate() {
        global $m_app; /* @var App $m_app */

        $m_app->caches->terminate();

        parent::terminate();
    }
}