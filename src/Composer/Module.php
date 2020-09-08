<?php

namespace Osm\Framework\Composer;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property Hooks|Hook[] $hooks @required
 */
class Module extends BaseModule
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'hooks': return $osm_app->cache->remember("composer_hooks", function($data) {
                return Hooks::new($data);
            });
        }
        return parent::default($property);
    }
}