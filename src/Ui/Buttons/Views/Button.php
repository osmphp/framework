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
 * @property bool $disabled @part
 * @property bool $outlined @part
 */
class Button extends View
{
    public $template = 'Osm_Ui_Buttons.button';

    protected function default($property) {
        switch ($property) {
            case 'color': return 'primary';
        }
        return parent::default($property);
    }
}