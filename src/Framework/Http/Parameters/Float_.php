<?php

namespace Osm\Framework\Http\Parameters;

use Osm\Framework\Http\Exceptions\InvalidParameter;

class Float_ extends String_
{
    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return null;
        }

        if (($result = osm_parse_float($value)) === false) {
            throw new InvalidParameter(osm_t("Parameter ':name' should be a number", [
                'name' => $this->name,
            ]));
        }

        return $result;
    }

    public function generate(&$query, $value) {
        $query[$this->name] = (string)$value;
    }
}