<?php

namespace Osm\Ui\Forms\Views;

use Osm\Framework\Views\Views\Container;
use Osm\Ui\Menus\Views\MenuBar;

/**
 * @property string $title @required @part
 * @property MenuBar $menu @part
 * @property string $type @part
 * @property string $modifier @part Reserved for image field and other
 *      custom section views
 */
class Section extends Container
{
    public $template = 'Osm_Ui_Forms.section';
}