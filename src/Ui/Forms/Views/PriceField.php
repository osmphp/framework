<?php

namespace Osm\Ui\Forms\Views;

/**
 * @property string $placeholder @part
 * @property string $autocomplete @part
 */
class PriceField extends Field
{
    public $template = 'Osm_Ui_Forms.string-field';
    public $view_model = 'Osm_Ui_Forms.StringField';
}