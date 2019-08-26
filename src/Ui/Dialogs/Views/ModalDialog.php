<?php

namespace Osm\Ui\Dialogs\Views;

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;

/**
 * @property string $header @part
 * @property View $footer @part
 */
class ModalDialog extends Container
{
    public $template = 'Osm_Ui_Dialogs.modal-dialog';
    public $view_model = 'Osm_Ui_Dialogs.ModalDialog';
    public $id_ = '{{ id }}';
    public $width = '{{ width }}';
    public $height = '{{ height }}';

}