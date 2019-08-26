<?php

namespace Osm\Samples\Compilation\Traits;

trait SampleTrait
{
    protected function around_someMethod(callable $proceed) {
        return "{$proceed()}, world";
    }

    public function addedMethod() {
        return $this->someMethod();
    }
}