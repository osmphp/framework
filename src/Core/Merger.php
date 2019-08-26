<?php

namespace Osm\Core;

class Merger
{
    public static function merge($target, ...$sources) {
        foreach ($sources as $source) {
            $target = static::mergeFromSource($target, $source);
        }

        return $target;
    }

    protected static function mergeFromSource($target, $source) {
        if (is_object($target)) {
            return static::mergeIntoObject($target, $source);
        }
        elseif (is_array($target)) {
            return static::mergeIntoArray($target, $source);
        }
        else {
            return $source;
        }
    }

    protected static function mergeIntoObject($target, $source) {
        foreach ($source as $key => $value) {
            if (property_exists($target, $key)) {
                $target->$key = static::merge($target->$key, $value);
            }
            else {
                $target->$key = $value;
            }
        }

        return $target;
    }

    protected static function mergeIntoArray($target, $source) {
        foreach ($source as $key => $value) {
            if (is_numeric($key)) {
                $target[] = $value;
            }
            elseif (isset($target[$key])) {
                $target[$key] = static::merge($target[$key], $value);
            }
            else {
                $target[$key] = $value;
            }
        }

        return $target;
    }
}