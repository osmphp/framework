<?php

namespace Osm\Framework\Views\Views;

use Osm\Core\App;
use Osm\Framework\Data\Sorter;
use Osm\Framework\Views\View;

/**
 * @property string $element @part
 * @property string[] $attributes @required @part
 * @property View[] $views @required @part
 * @property View[] $views_ @required
 */
class Container extends View
{
    public $template = 'Osm_Framework_Views.container';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'views': return [];
            case 'views_': return $this->getViews();

            /** @noinspection PhpDuplicateSwitchCaseBodyInspection */
            case 'attributes': return [];
        }
        return parent::default($property);
    }

    protected function getViews() {
        $result = $this->views;
        $this->sorter->orderBy($result, 'sort_order');
        return $result;
    }
}