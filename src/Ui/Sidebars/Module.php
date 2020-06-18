<?php

namespace Osm\Ui\Sidebars;

use Osm\Core\Modules\BaseModule;
use Osm\Framework\Views\Views\Page;

class Module extends BaseModule
{
    public $hard_dependencies = [
        'Osm_Ui_Aba',
    ];

    public $traits = [
        Page::class => Traits\PageTrait::class,
    ];
}