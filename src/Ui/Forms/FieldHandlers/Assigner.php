<?php

namespace Osm\Ui\Forms\FieldHandlers;

use Osm\Ui\Forms\Views\Field;
use Osm\Ui\Forms\Views\Form;

/**
 * @property object $data
 */
class Assigner extends Handler
{
    public static function assign(Form $form, $data) {
        return static::new(['form' => $form, 'data' => $data])->handleForm();
    }

    protected function handleField(Field $field) {
        $field->assign($this->data);
    }
}