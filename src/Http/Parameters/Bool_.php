<?php

namespace Osm\Framework\Http\Parameters;

use Osm\Framework\Http\Parameter;

class Bool_ extends Parameter
{
    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return false;
        }

        return $value;
    }

    public function generate(&$query, $value) {
        if ($value) {
            $query[$this->name] = $value;
        }
    }
}