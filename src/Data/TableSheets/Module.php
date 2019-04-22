<?php

namespace Manadev\Data\TableSheets;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Data_Sheets',
        'Manadev_Data_TableQueries',
    ];
}