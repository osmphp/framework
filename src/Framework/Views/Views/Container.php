<?php

namespace Osm\Framework\Views\Views;

use Osm\Framework\Views\View;

/**
 * @property string $element @part
 * @property string[] $attributes @required @part
 * @property string $css_block @part
 *
 * @property View[] $items @required @part
 * @property View[] $items_ @required
 */
class Container extends View
{
    public $template = 'Osm_Framework_Views.container';

    protected function default($property) {
        switch ($property) {
            case 'items': return [];
            case 'items_': return $this->sortViews($this->items);
            case 'empty': return $this->isEmpty();

            /** @noinspection PhpDuplicateSwitchCaseBodyInspection */
            case 'attributes': return [];
        }
        return parent::default($property);
    }

    protected function isEmpty() {
        foreach ($this->items as $item) {
            if (!$item->empty) {
                return false;
            }
        }

        return true;
    }
}