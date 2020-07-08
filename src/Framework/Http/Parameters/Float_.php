<?php

namespace Osm\Framework\Http\Parameters;

class Float_ extends String_
{
    public $pattern = '/-?\d+(?:\.\d+)?/';

    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return null;
        }

        return floatval($value);
    }

    public function generate(&$query, $value) {
        $query[$this->name] = (string)$value;
    }
}