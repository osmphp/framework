<?php

namespace Osm\Ui\Forms\Views;

use Osm\Framework\Views\View;
use Osm\Framework\Views\Views\Container;

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
    public $template = 'Osm_Ui_Forms.form';
    public $view_model = 'Osm_Ui_Forms.Form';

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