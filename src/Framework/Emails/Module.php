<?php

namespace Osm\Framework\Emails;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;

/**
 * @property Transports|Transport[] $transports @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    public $hard_dependencies = [
        'Osm_Framework_Queues',
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'transports': return $osm_app->cache->remember("email_transports", function($data) {
                return Transports::new($data);
            });
        }
        return parent::default($property);
    }
}