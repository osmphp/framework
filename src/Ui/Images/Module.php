<?php

namespace Osm\Ui\Images;

use Osm\Core\Modules\BaseModule;
use Osm\Data\Files\File;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Data_Files',
    ];

    public $traits = [
        File::class => Traits\FileTrait::class,
    ];
}