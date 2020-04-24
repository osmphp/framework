<?php

namespace Osm\Ui\Inputs\Views;

use Osm\Data\Sheets\Search;
use Osm\Framework\Views\View;
use Osm\Ui\Forms\FormPart;

/**
 * @property string $name @required @part
 * @property string $type @required @part
 * @property string $title @part
 * @property string $placeholder @part
 * @property string $comment @part
 * @property string $value @part
 * @property bool $required @part
 * @property string $autocomplete @part
 * @property bool $focus @part
 * @property string $autocomplete_prefix @part Prefix added to element name to
 *      scope browser auto-completion
 */
class Input extends View implements FormPart
{
    public $template = 'Osm_Ui_Inputs.input';
    public $view_model = 'Osm_Ui_Inputs.Input';

    protected function default($property) {
        switch ($property) {
            case 'type': return $this->getType();
            case 'name': return $this->getName();
            case 'autocomplete': return $this->type == 'password' ? 'new-password' : null;
        }
        return parent::default($property);
    }

    protected function getType() {
        if (!$this->modifier) {
            return 'text';
        }

        if (strpos($this->modifier, '-password') !== false) {
            return 'password';
        }

        return 'text';
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
        $this->model = osm_merge([],
            $this->required ? ['required' => $this->required] : [],
            $this->focus ? ['focus' => $this->focus] : [],
            $this->autocomplete_prefix
                ? ['autocomplete_prefix' => $this->autocomplete_prefix]
                : [],
            $this->model ?: []);

        $this->layout->loadLayer([
            '#page' => [
                'translations' => [
                    "Fill in this field" => "Fill in this field",
                ],
            ],
        ]);
    }

    public function addFormPartToSearch(Search $search) {
        if ($this->type != 'password') {
            $search->select($this->name);
        }
    }

    /**
     * @param object $data
     */
    public function assignFormPartValue($data) {
        if ($this->type != 'password') {
            $this->value = $data->{$this->name};
        }
    }

    public function assignFormAutocompletePrefix($prefix) {
        if (!$this->autocomplete_prefix) {
            $this->autocomplete_prefix = $prefix;
        }
    }

    public function assignFormFocus() {
        $this->focus = true;

        return true;
    }
}