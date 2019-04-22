<?php

namespace Manadev\Ui\SnackBars\Controllers;

use Manadev\Framework\Http\Controller;
use Manadev\Ui\SnackBars\Views\SnackBar;

class Web extends Controller
{
    public function getMessageTemplate() {
        return SnackBar::new(['template' => 'Manadev_Ui_SnackBars.message']);
    }

    public function getExceptionTemplate() {
        return SnackBar::new([
            'template' => 'Manadev_Ui_SnackBars.exception',
            'view_model' => 'Manadev_Ui_SnackBars.Exception',
        ]);
    }
}