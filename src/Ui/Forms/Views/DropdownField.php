<?php

namespace Osm\Ui\Forms\Views;

use Osm\Core\App;
use Osm\Data\OptionLists\OptionList;

/**
 * @property string $option_list @part
 * @property OptionList $option_list_
 * @property string[] $options @part
 * @property string[] $options_ @required
 */
class DropdownField extends Field
{
    public $field_template = 'Osm_Ui_Forms.dropdown-field';
    public $view_model = 'Osm_Ui_Forms.DropdownField';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'modifier': return '-dropdown';
            case 'option_list_': return $this->option_list
                ? $osm_app->option_lists[$this->option_list]
                : null;
            case 'options_': return $this->options ?: $this->option_list_->items;
        }
        return parent::default($property);
    }
}