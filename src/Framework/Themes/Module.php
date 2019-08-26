<?php

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;

/**
 * @property Current $current
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'current': return $m_app[Current::class];
        }
        return parent::default($property);
    }
}