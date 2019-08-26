<?php

namespace Osm\Ui\Inputs;

use Osm\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Ui_Aba',
        'Osm_Ui_Forms',
    ];
}