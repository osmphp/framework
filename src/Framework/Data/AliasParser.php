<?php

namespace Osm\Framework\Data;

use Osm\Core\Object_;

class AliasParser extends Object_
{
    const EXPR = 0;
    const ALIAS = 1;

    public function parse($expr) {
        if (($pos = strripos(strtolower($expr), ' as ')) === false) {
            return null;
        }

        return [
            trim(substr($expr, 0, $pos)),
            trim(substr($expr, $pos + strlen(' as '))),
        ];
    }

    public function expr($expr) {
        $parsed = $this->parse($expr);
        return $parsed ? $parsed[static::EXPR] : $expr;
    }

    public function alias($expr) {
        $parsed = $this->parse($expr);
        return $parsed ? $parsed[static::ALIAS] : $expr;
    }
}