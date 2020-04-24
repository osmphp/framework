<?php

namespace Osm\Ui\Forms\Views;

use Osm\Data\Sheets\Search;
use Osm\Framework\Views\View;

/**
 * @property string $name @required @part
 * @property string $title @part
 * @property string $comment @part
 * @property mixed $value @part
 * @property bool $required @part
 * @property bool $focus @part
 * @property bool $focusable @part
 * @property string $prefix @part Prefix added to element name to scope
 *      browser auto-completion. Fields ignore
 *
 * @property string $field_template @required @part
 */
abstract class Field extends View
{
    public $template = 'Osm_Ui_Forms.field-wrap';

    protected function default($property) {
        switch ($property) {
            case 'name': return $this->getName();
            case 'focusable': return true;
        }
        return parent::default($property);
    }

    protected function getName() {
        $result = $this->alias;

        // cut the prefix
        if (mb_strpos($result, 'items_') === 0) {
            $result = mb_substr($result, mb_strlen('items_'));
        }

        return $result;
    }

    public function rendering() {
        $this->model = osm_merge([
            'required' => $this->required,
            'focus' => $this->focus,
            'prefix' => $this->prefix
        ], $this->model ?: []);
    }

    /**
     * Fetches data for this field from the data source
     *
     * @param Search $search
     */
    public function fetch(Search $search) {
        $search->select($this->name);
    }

    /**
     * Assigns fetched data to the field
     *
     * @param object $data
     */
    public function assign($data) {
        $this->value = $data->{$this->name};
    }
}