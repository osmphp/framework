<?php

namespace Manadev\Framework\Views\Views;

use Manadev\Framework\Views\View;

/**
 * @property string $element @part
 * @property View[] $views @required @part
 */
class Container extends View
{
    public $template = 'Manadev_Framework_Views.container';

    protected function default($property) {
        switch ($property) {
            case 'views': return [];
        }
        return parent::default($property);
    }
}