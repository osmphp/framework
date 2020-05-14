<?php

namespace Osm\Ui\Forms\Views;

use Osm\Data\Sheets\Search;

class PasswordField extends InputField
{
    public $view_model = 'Osm_Ui_Forms.PasswordField';
    public $type = 'password';
    public $autocomplete = 'new-password';

    public function fetch(Search $search) {
        // don't fetch password hash from the DB
    }

    public function assign($data) {
        // don't assign password value to a form control
    }
}