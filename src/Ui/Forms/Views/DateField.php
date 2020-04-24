<?php

namespace Osm\Ui\Forms\Views;

class DateField extends InputField
{
    public $view_model = 'Osm_Ui_Forms.DateField';
    public $type = 'date';

    protected function default($property) {
        switch ($property) {
            case 'modifier': return '-date';
        }
        return parent::default($property);
    }
}