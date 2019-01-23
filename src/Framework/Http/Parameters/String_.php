<?php

namespace Manadev\Framework\Http\Parameters;

use Manadev\Framework\Http\Exceptions\InvalidParameter;
use Manadev\Framework\Http\Parameter;

/**
 * @property string $pattern @part
 */
class String_ extends Parameter
{
    public function parse($query) {
        if (!($value = parent::parse($query))) {
            return null;
        }

        if (!is_string($value)) {
            throw new InvalidParameter(m_("Parameter ':name' should be a string and used once", [
                'name' => $this->name,
            ]));

        }

        if ($this->pattern && !preg_match($this->pattern, $value)) {
            throw new InvalidParameter(m_("Parameter ':name' value ':value' should match pattern ':pattern'", [
                'name' => $this->name,
                'value' => $value,
                'pattern' => $this->pattern,
            ]));
        }

        return $value;
    }
}