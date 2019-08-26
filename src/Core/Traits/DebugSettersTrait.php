<?php

namespace Osm\Core\Traits;

trait DebugSettersTrait
{
    public function __set($property, $value) {
        $this->$property = $value;

        if (is_array($value)) {
            $value = 'array';
        }
        elseif (is_object($value)) {
            $value = 'object';
        }
        else {
            $value = "'{$value}'";
        }
        m_core_log(get_class($this) . "::\${$property} = {$value}", 'setter.log');
        m_core_log_stack_trace('setter.log');
    }
}