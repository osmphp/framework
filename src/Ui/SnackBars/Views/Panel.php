<?php

namespace Manadev\Ui\SnackBars\Views;

use Manadev\Core\App;
use Manadev\Framework\Views\View;

class Panel extends View
{
    public $template = 'Manadev_Ui_SnackBars.panel';

    public function rendering() {
        global $m_app; /* @var App $m_app */

        $this->js_config->translate("Request processing was interrupted.");
        $this->js_config->translate("Processing ...");
        $this->js_config->close_snack_bars_after = $m_app->settings->close_snack_bars_after;
    }
}