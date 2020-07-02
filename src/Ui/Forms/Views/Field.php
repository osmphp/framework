<?php

namespace Osm\Ui\Forms\Views;

use Osm\Data\Sheets\Search;
use Osm\Framework\Views\Views\Container;

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
 * @property string $type @part
 *
 * @property string $field_template @required @part
 *
 * Styles (each view class may support these or not):
 *
 * @property string $body_color @part
 * @property string $body_on_color @part
 *
 * Computed properties:
 *
 * @property string $body_color_
 * @property string $body_on_color_
 */
abstract class Field extends Container
{
    public $template = 'Osm_Ui_Forms.field-wrap';

    protected function default($property) {
        switch ($property) {
            case 'name': return $this->getName();
            case 'focusable': return true;

            case 'body_color': return $this->color;
            case 'body_on_color': return $this->on_color;
            case 'body_color_': return $this->cssPrefix($this->body_color);
            case 'body_on_color_': return $this->cssPrefix($this->body_on_color, '-on-');
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

    public function assign($data) {
        $this->value = $data->{$this->name};

        return $this;
    }

    protected function isEmpty() {
        return false;
    }
}