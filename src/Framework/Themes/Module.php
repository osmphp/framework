<?php

namespace Manadev\Framework\Themes;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;

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