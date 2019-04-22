<?php

namespace Manadev\Ui\Dialogs\Controllers;

use Manadev\Framework\Http\Controller;
use Manadev\Ui\Dialogs\Views\ModalDialog;
use Manadev\Ui\SnackBars\Views\SnackBar;

class Web extends Controller
{
    public function exceptionDialog() {
        return m_layout('dialogs_exception');
    }

    public function yesNoDialog() {
        return m_layout('dialogs_yes_no');
    }
}