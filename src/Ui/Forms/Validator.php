<?php

namespace Osm\Ui\Forms;

use Osm\Ui\Forms\Views\Form;

class Validator extends Handler
{
    public static function validate(Form $form) {
        return static::new(['form' => $form])->handleForm();
    }

    protected function handleFormPart(FormPart $view) {

    }
}