<?php

namespace Manadev\Samples\Ui;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Ui_DataTables',
        'Manadev_Data_TableSheets',
        'Manadev_Samples_Js',
    ];
}