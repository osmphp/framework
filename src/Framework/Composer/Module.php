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
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'hooks': return $m_app->cache->remember("composer_hooks", function($data) {
                return Hooks::new($data);
            });
        }
        return parent::default($property);
    }
}