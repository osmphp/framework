<?php

namespace Osm\Ui\Forms;

use Osm\Data\Search\Search;
use Osm\Ui\Forms\Views\Form;

/**
 * @property Search $search @required
 * @property int $id
 */
class Loader extends Handler
{
    protected function default($property) {
        switch ($property) {
            case 'search': return $this->form->search_;
            case 'id': return $this->form->row_id;
        }
        return parent::default($property);
    }

    public static function load(Form $form) {
        return static::new(['form' => $form])->handleForm();
    }

    protected function handleForm() {
        if (!$this->id) {
            return null;
        }

        $this->search->id($this->id);
        parent::handleForm();
        return $this->search->get()->items->first();
    }

    protected function handleFormPart(FormPart $view) {
        $view->addFormPartToSearch($this->search);
    }
}