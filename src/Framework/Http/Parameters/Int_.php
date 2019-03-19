<?php

namespace Manadev\Framework\Http\Parameters;

class Int_ extends String_
{
    public $pattern = '/-?\d+/';

    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return null;
        }

        return intval($value);
    }

    public function generate(&$query, $value) {
        $query[$this->name] = (string)$value;
    }
}