<?php

namespace Osm\Ui\Forms\Views;

/**
 * @property string $type @part @required
 * @property string $placeholder @part
 * @property string $autocomplete @part
 * @property string $step @part
 */
abstract class InputField extends Field
{
    public $field_template = 'Osm_Ui_Forms.input-field';
}