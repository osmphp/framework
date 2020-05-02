<?php

namespace Osm\Ui\SnackBars\Views;

use Osm\Core\App;
use Osm\Framework\Views\View;

class Panel extends View
{
    public $template = 'Osm_Ui_SnackBars.panel';

    protected function default($property) {
        switch ($property) {
            case 'debug_selector': return '.snack-bar-panel';
        }
        return parent::default($property);
    }

    public function rendering() {
        global $osm_app; /* @var App $osm_app */

        $this->layout->loadLayer([
            '#page' => [
                'translations' => [
                    "Request processing was interrupted." => "Request processing was interrupted.",
                    "Processing ..." => "Processing ...",
                ],
                'model' => [
                    'close_snack_bars_after' => $osm_app->settings->close_snack_bars_after,
                ],
            ],
        ]);
    }
}