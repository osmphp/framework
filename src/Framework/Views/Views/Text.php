<?php

namespace Osm\Framework\Views\Views;

use Osm\Framework\Views\View;

/**
 * @property string $contents @part
 * @property string $tag @required @part
 */
class Text extends View
{
    public $template = 'Osm_Framework_Views.text';

    protected function default($property) {
        switch ($property) {
            case 'tag': return 'p';
        }
        return parent::default($property);
    }
}