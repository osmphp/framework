<?php

namespace Manadev\Ui\Menus;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Framework\Areas\Area;
use Manadev\Ui\Menus\Items\Types;

/**
 * @property Types $item_types @required
 */
class Module extends BaseModule
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'item_types': return $m_app->cache->remember('menu_item_types', function($data) {
                return Types::new($data);
            });
        }
        return parent::default($property);
    }
}