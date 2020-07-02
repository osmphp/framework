<?php

namespace Osm\Ui\SnackBars\Views;

use Osm\Framework\Views\View;

/**
 * @property string $modifier @part
 */
class SnackBar extends View
{
    public $id_ = '{{ id }}';
    public $on_color = 'message';
    public $color = 'neutral';
}