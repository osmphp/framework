<?php

namespace Osm\Ui\Forms\Views;

use Osm\Ui\Menus\Views\MenuBar;

/**
 * @property string $title @required @part
 * @property MenuBar $menu @part
 * @property string $type @part
 * @property string $state @part Reserved for image field and other
 *      custom section views
 */
class SectionField extends Field
{
    public $template = 'Osm_Ui_Forms.section';
}