<?php

namespace Osm\Samples\Compilation;

use Osm\Core\Modules\BaseModule;
use Osm\Samples\Compilation\Traits\SampleTrait;

class Module extends BaseModule
{
    public $traits = [
        SampleClass::class => SampleTrait::class,
    ];

    public function boot() {
        parent::boot();

        $object = SampleClass::new();
        $message = $object->addedMethod();
    }
}