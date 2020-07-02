<?php

namespace Osm\Ui\SnackBars\Controllers;

use Osm\Framework\Http\Controller;
use Osm\Ui\SnackBars\Views\SnackBar;

class Web extends Controller
{
    public function getMessageTemplate() {
        return SnackBar::new([
            'template' => 'Osm_Ui_SnackBars.message',
        ]);
    }

    public function getExceptionTemplate() {
        return SnackBar::new([
            'template' => 'Osm_Ui_SnackBars.exception',
            'modifier' => '-exception',
        ]);
    }
}