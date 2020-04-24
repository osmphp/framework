<?php

namespace Osm\Ui\Forms\Views;

/**
 * @property string $placeholder @part
 * @property string $autocomplete @part
 */
class StringField extends Field
{
    public $field_template = 'Osm_Ui_Forms.string-field';
    public $view_model = 'Osm_Ui_Forms.StringField';
}