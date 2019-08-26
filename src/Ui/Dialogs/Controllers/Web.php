<?php

namespace Osm\Ui\Dialogs\Controllers;

use Osm\Framework\Http\Controller;

class Web extends Controller
{
    public function exceptionDialog() {
        return osm_layout('dialogs_exception');
    }

    public function yesNoDialog() {
        return osm_layout('dialogs_yes_no');
    }
}