<?php

namespace Osm\Framework\Data\Traits;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Profiler;
use Osm\Framework\Data\MethodCall;

trait CloneableTrait
{
    /**
     * @temp
     * @var bool
     */
    public $prevent_registering_method_calls = false;
    public $method_calls = [];

    protected function registerMethodCall($name, ...$args) {
        if (!$this->prevent_registering_method_calls) {
            $this->method_calls[] = MethodCall::new(compact('name', 'args'));
        }
    }

    /**
     * @param string[] $methods One or more method calls to be reproduced. Use constants from Part class
     * @return Object_
     */
    public function clone(...$methods) {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if ($osm_profiler) $osm_profiler->start('clone', 'helpers');
        try {
            $result = static::new([], null, $this->parent);
            $this->callMethodsOn($result, ...$methods);
            return $result;
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop('clone');
        }
    }

    /**
     * @param string $class
     * @param string[] $methods One or more method calls to be reproduced. Use constants from Part class
     * @return Object_
     * @throws \Osm\Core\Exceptions\FactoryError
     */
    public function cloneAs($class, ...$methods) {
        global $osm_app; /* @var App $osm_app */

        $result = $osm_app->create($class, [], null, $this->parent);
        $this->callMethodsOn($result, ...$methods);
        return $result;
    }

    public function callMethodsOn($result, ...$methods) {
        $empty = empty($methods);
        if (!$empty && is_callable($methods[0])) {
            foreach ($this->method_calls as $call) {
                if ($methods[0]($call)) {
                    call_user_func_array([$result, $call->name], $call->args);
                }
            }
            return;
        }

        $invert = false;
        if (!$empty && $methods[0] === '!') {
            $invert = true;
            array_shift($methods);
        }

        foreach ($this->method_calls as $call) {
            if ($empty || (in_array($call->name, $methods) xor $invert)) {
                call_user_func_array([$result, $call->name], $call->args);
            }
        }
    }
}