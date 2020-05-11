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
 * @property bool $disabled @part
 */
class Button extends View
{
    public $template = 'Osm_Ui_Buttons.button';

    protected function default($property) {
        switch ($property) {
            case 'color': return '-primary';
        }
        return parent::default($property);
    }

    public function on($color) {
        return implode(' ', array_map(function($color) {
            return "-on{$color}";
        }, explode(' ', $color)));
    }
}