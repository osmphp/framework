<?php

namespace Osm\Ui\Buttons\Views;


use Osm\Framework\Views\View;

/**
 * @property string $title @part
 * @property string $url @part
 *
 * Style properties:
 *
 * @property string $icon @part
 * @property bool $main @part
 * @property bool $dangerous @part
 */
class Button extends View
{
    public $template = 'Osm_Ui_Buttons.button';
}