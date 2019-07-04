<?php

namespace Manadev\Framework\Http\Parameters;

use Manadev\Framework\Http\Parameter;

class Bool_ extends Parameter
{
    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return false;
        }

        return $value;
    }
}