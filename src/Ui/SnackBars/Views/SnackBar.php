<?php

namespace Osm\Ui\SnackBars\Views;

use Osm\Framework\Views\View;

class SnackBar extends View
{
    public $id_ = '{{ id }}';
    public $view_model = 'Osm_Ui_SnackBars.SnackBar';
    public $on_color = 'message';
    public $color = 'neutral';
}