<?php

namespace Osm\Framework\Views\Views;

use Osm\Framework\Views\View;

/**
 * @property string $element @part
 * @property string[] $attributes @required @part
 *
 * @property View[] $views @required @part
 * @property View[] $views_ @required
 */
class Container extends View
{
    public $template = 'Osm_Framework_Views.container';

    protected function default($property) {
        switch ($property) {
            case 'views': return [];
            case 'views_': return $this->sortViews($this->views);
            case 'empty': return !count($this->views);

            /** @noinspection PhpDuplicateSwitchCaseBodyInspection */
            case 'attributes': return [];
        }
        return parent::default($property);
    }
}