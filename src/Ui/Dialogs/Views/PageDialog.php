<?php

namespace Osm\Ui\Dialogs\Views;

use Osm\Framework\Views\View;

/**
 * @property int $width @required @part Dialog width in pixels
 * @property View $content @required @part
 */
class PageDialog extends View
{
    public $template = 'Osm_Ui_Dialogs.page-dialog';
    public $view_model = 'Osm_Ui_Dialogs.PageDialog';

    public function rendering() {
        $this->model = osm_merge(['width' => $this->width],
            $this->model ?: []);
    }
}