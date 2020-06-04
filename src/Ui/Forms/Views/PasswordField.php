<?php

namespace Osm\Ui\Forms\Views;

class PasswordField extends InputField
{
    public $view_model = 'Osm_Ui_Forms.PasswordField';
    public $type = 'password';
    public $autocomplete = 'new-password';

    public function assign($data) {
        // don't assign password value to a form control
    }
}