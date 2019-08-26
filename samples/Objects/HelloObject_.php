<?php

namespace Osm\Samples\Objects;

class HelloObject_
{
    public $accessed = 0;

    public function __isset($property) {
        try {
            return $this->__get($property) !== null;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function __get($property) {
        if (isset($this->$property)) {
            return $this->$property;
        }
        $this->accessed++;
        return $this->$property = ['hello' => 'world'];
    }
}