<?php

namespace Manadev\Ui\Tabs;

use Manadev\Core\Object_;
use Manadev\Framework\Views\View;
use Manadev\Ui\Tabs\Views\Tabs;

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