<?php

namespace Osm\Ui\Forms;

use Osm\Ui\Forms\Views\Form;

/**
 * @property object $data
 */
class Assigner extends Handler
{
    public static function assign(Form $form, $data) {
        return static::new(['form' => $form, 'data' => $data])->handleForm();
    }

    protected function handleFormPart(FormPart $view) {
        $view->assignFormPartValue($this->data);
    }
}