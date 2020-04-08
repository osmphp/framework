<?php

namespace Osm\Ui\Forms;

use Osm\Ui\Forms\Views\Form;

class AutocompletePrefixAssigner extends Handler
{
    public static function assign(Form $form) {
        return static::new(['form' => $form])->handleForm();
    }

    protected function handleFormPart(FormPart $view) {
        $view->assignFormAutocompletePrefix($this->form->autocomplete_prefix);
    }
}