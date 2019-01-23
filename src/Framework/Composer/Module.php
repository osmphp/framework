<?php

namespace Manadev\Framework\Composer;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;

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