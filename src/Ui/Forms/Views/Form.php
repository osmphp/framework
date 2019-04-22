<?php

namespace Manadev\Ui\Forms\Views;

use Manadev\Framework\Views\View;
use Manadev\Framework\Views\Views\Container;

/**
 * @property View $header @part
 * @property View $footer @part
 * @property string $route @required @part
 * @property string $method @required
 * @property string $action @required
 * @property string $submitting_message @part
 */
class Form extends Container
{
    public $template = 'Manadev_Ui_Forms.form';
    public $view_model = 'Manadev_Ui_Forms.Form';

    protected function default($property) {
        switch ($property) {
            case 'method': return substr($this->route, 0, strpos($this->route, ' '));
            case 'action': return substr($this->route, strpos($this->route, ' ') + 1);
            case 'model': return $this->getModel();
        }
        return parent::default($property);
    }

    protected function getModel() {
        return (object)[
            'submitting_message' => (string)$this->submitting_message,
        ];
    }
}