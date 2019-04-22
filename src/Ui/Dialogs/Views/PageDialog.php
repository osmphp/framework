<?php

namespace Manadev\Ui\Dialogs\Views;

use Manadev\Framework\Views\View;

/**
 * @property int $width @required @part Dialog width in pixels
 * @property View $content @required @part
 */
class PageDialog extends View
{
    public $template = 'Manadev_Ui_Dialogs.page-dialog';
    public $view_model = 'Manadev_Ui_Dialogs.PageDialog';

    protected function default($property) {
        switch ($property) {
            case 'model': return (object)['width' => $this->width];
        }
        return parent::default($property);
    }
}