<?php

namespace Osm\Ui\SnackBars\Views;

use Osm\Core\App;
use Osm\Framework\Views\View;

class Panel extends View
{
    public $template = 'Osm_Ui_SnackBars.panel';

    public function rendering() {
        global $osm_app; /* @var App $osm_app */

        $this->js_config->translate("Request processing was interrupted.");
        $this->js_config->translate("Processing ...");
        $this->js_config->close_snack_bars_after = $osm_app->settings->close_snack_bars_after;
    }
}