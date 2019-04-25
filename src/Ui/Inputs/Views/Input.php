<?php

namespace Manadev\Ui\Inputs\Views;

use Manadev\Framework\Views\View;

/**
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string $title @part
 * @property string $placeholder @part
 * @property string $comment @part
 * @property string $value @part
 * @property bool $required @part
 * @property string $autocomplete @part
 * @property bool $focus @part
 */
class Input extends View
{
    public $template = 'Manadev_Ui_Inputs.input';
    public $view_model = 'Manadev_Ui_Inputs.Input';

    protected function default($property) {
        switch ($property) {
            case 'type': return $this->getType();
            case 'alias': return $this->name;
            case 'model': return $this->getModel();
            case 'autocomplete': return $this->type == 'password' ? 'new-password' : null;
        }
        return parent::default($property);
    }

    protected function getType() {
        if (!$this->modifier) {
            return 'text';
        }

        if (strpos($this->modifier, '-password') !== false) {
            return 'password';
        }

        return 'text';
    }

    protected function getModel() {
        $this->js_config->translate("Fill in this field");

        $result = [];
        if ($this->required) {
            $result['required'] = $this->required;
        }
        if ($this->focus) {
            $result['focus'] = $this->focus;
        }
        return (object)$result;
    }
}