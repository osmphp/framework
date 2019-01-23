<?php

namespace Manadev\Core;

class Promise
{
    public $object;
    public $method;
    public $args;

    public function __construct($object, $method, $args) {
        $this->method = $method;
        $this->args = $args;
        $this->object = $object;
    }

    public function get($method = null) {
        global $m_app; /* @var App $m_app */

        $object = $this->object ? $m_app->{$this->object} : $m_app;
        if (!$method) {
            $method = $this->method;
        }

        return call_user_func_array([$object, $method], $this->args);
    }

    /**
     * @return string
     */
    public function __toString() {
        global $m_app; /* @var App $m_app */

        try {
            return $this->get();
        }
        catch (\Throwable $e) {
            if (!$m_app->pending_exception) {
                $m_app->pending_exception = $e;
            }
            return '';
        }
    }

    public function toArray() {
        return m_array($this->get());
    }

    public function toObject() {
        return m_object($this->get());
    }
}