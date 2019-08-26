<?php

namespace Osm\Framework\Encryption;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;
use Osm\Framework\Encryption\Hashing\Hashing;
use Osm\Framework\Encryption\Hashing\Hashings;
use Osm\Framework\Settings\Settings;

/**
 * @property Hashings|Hashing[] $hashings @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    public $hard_dependencies = [
        'Osm_Framework_Settings',
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'hashings': return $m_app->cache->remember('hashings', function($data) {
                return Hashings::new($data);
            });
        }
        return parent::default($property);
    }
}