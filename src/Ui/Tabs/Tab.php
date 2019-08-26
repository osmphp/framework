<?php

namespace Osm\Ui\Tabs;

use Osm\Core\Object_;
use Osm\Framework\Views\View;
use Osm\Ui\Tabs\Views\Tabs;

/**
 * @property Tabs $parent @required
 * @property string $name @required @part
 * @property string $title @required @part
 * @property bool $active @part
 * @property View $view @part
 */
class Tab extends Object_
{

}