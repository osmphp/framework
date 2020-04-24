<?php

namespace Osm\Ui\Forms\FieldHandlers;

use Osm\Ui\Forms\Views\Field;
use Osm\Ui\Forms\Views\Form;

/**
 * @property bool $set First form controls sets this property to prevent others
 * @property array $data
 */
class Preparer extends Handler
{
    public static function prepare(Form $form, $data = []) {
        return static::new(['form' => $form, 'data' => $data])->handleForm();
    }

    protected function handleField(Field $field) {
        $field->set($this->data);

        if ($this->set) {
            return;
        }

        if ($field->focusable) {
            $field->focus = true;
            $this->set = true;
        }
    }
}