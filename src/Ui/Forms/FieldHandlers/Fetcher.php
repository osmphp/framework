<?php

namespace Osm\Ui\Forms\FieldHandlers;

use Osm\Data\Sheets\Search;
use Osm\Ui\Forms\Views\Field;
use Osm\Ui\Forms\Views\Form;

/**
 * @property Search $search @required
 * @property int $id
 */
class Fetcher extends Handler
{
    protected function default($property) {
        switch ($property) {
            case 'search': return $this->form->search;
            case 'id': return $this->form->row_id;
        }
        return parent::default($property);
    }

    public static function fetch(Form $form) {
        return static::new(['form' => $form])->handleForm();
    }

    protected function handleForm() {
        if (!$this->id) {
            return null;
        }

        $this->search->forDisplay();

        parent::handleForm();

        return $this->search->get()->items->first();
    }

    protected function handleField(Field $field) {
        $field->fetch($this->search);
    }
}