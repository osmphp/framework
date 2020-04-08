<?php

namespace Osm\Ui\Forms;

use Osm\Ui\Forms\Views\Form;

/**
 * @property bool $set First form controls sets this property to prevent others
 */
class FocusAssigner extends Handler
{
    public static function assign(Form $form) {
        return static::new(['form' => $form])->handleForm();
    }

    protected function handleFormPart(FormPart $view) {
        if ($this->set) {
            return;
        }

        if ($view->assignFormFocus()) {
            $this->set = true;
        }
    }
}