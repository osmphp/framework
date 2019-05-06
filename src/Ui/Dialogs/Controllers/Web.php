<?php

namespace Manadev\Ui\Dialogs\Controllers;

use Manadev\Framework\Http\Controller;

class Web extends Controller
{
    public function exceptionDialog() {
        return m_layout('dialogs_exception');
    }

    public function yesNoDialog() {
        return m_layout('dialogs_yes_no');
    }
}