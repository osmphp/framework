<?php

namespace Manadev\Ui\Menus\Items;

use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $model_class @required @part
 *
 * @see \Manadev\Ui\MenuBars\Module
 *      @property string $menu_bar_template @required @part
 * @see \Manadev\Ui\PopupMenus\Module
 *      @property string $popup_menu_template @required @part
 */
class Type extends Object_
{
    const SEPARATOR = 'separator';
    const PLACEHOLDER = 'placeholder';
    const LABEL = 'label';
    const SUBMENU = 'submenu';
    const INPUT = 'input';
    const COMMAND = 'command';
    const LINK = 'link';

    protected function default($property) {
        switch ($property) {
            case 'model_class': return Item::class;
        }
        return parent::default($property);
    }
}