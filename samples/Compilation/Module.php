<?php

namespace Manadev\Samples\Compilation;

use Manadev\Core\Modules\BaseModule;
use Manadev\Samples\Compilation\Traits\SampleTrait;

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