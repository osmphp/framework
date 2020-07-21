<?php

namespace Osm\Ui\Buttons\Views;


use Osm\Framework\Views\View;

/**
 * @property string $title @part
 * @property string $url @part
 * @property bool $no_follow @part
 *
 * Style properties:
 *
 * @property string $icon @part
 * @property bool $disabled @part
 * @property bool $outlined @part
 */
class Button extends View
{
    public $template = 'Osm_Ui_Buttons.button';
}