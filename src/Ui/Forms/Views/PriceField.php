<?php

namespace Osm\Ui\Forms\Views;

class PriceField extends InputField
{
    public $field_template = 'Osm_Ui_Forms.price-field';
    public $view_model = 'Osm_Ui_Forms.PriceField';
    public $type = 'number';

    protected function default($property) {
        switch ($property) {
            case 'modifier': return '-price';
        }
        return parent::default($property);
    }
}