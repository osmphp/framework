<?php

namespace Osm\Framework\Themes;

use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $parent_theme @part
 * @property Definition[] $definitions @required @part
 * @property string[] $view_classes @required @part
 */
class Theme extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'view_classes': return [];
        }

        return parent::default($property);
    }
}