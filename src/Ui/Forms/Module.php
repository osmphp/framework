<?php

namespace Manadev\Ui\Forms;

use Manadev\Core\Modules\BaseModule;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Manadev_Ui_Aba',
        'Manadev_Ui_SnackBars',
    ];
}