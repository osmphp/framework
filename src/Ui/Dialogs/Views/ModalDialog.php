<?php

namespace Manadev\Ui\Dialogs\Views;

use Manadev\Framework\Views\View;
use Manadev\Framework\Views\Views\Container;

/**
 * @property string $header @part
 * @property View $footer @part
 */
class ModalDialog extends Container
{
    public $template = 'Manadev_Ui_Dialogs.modal-dialog';
    public $view_model = 'Manadev_Ui_Dialogs.ModalDialog';
    public $id_ = '{{ id }}';
    public $width = '{{ width }}';
    public $height = '{{ height }}';

}