<?php

namespace Osm\Ui\Forms\Views;

class PriceField extends InputField
{
    public $field_template = 'Osm_Ui_Forms.price-field';
    public $view_model = 'Osm_Ui_Forms.PriceField';
    public $type = 'price';
    public $autocomplete = 'off';
}