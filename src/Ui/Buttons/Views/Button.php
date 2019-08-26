<?php

namespace Osm\Ui\Buttons\Views;


use Osm\Framework\Views\View;

/**
 * @property string $title @part
 * @property string $icon @part
 * @property string $url @part
 */
class Button extends View
{
    public $template = 'Osm_Ui_Buttons.button';
}