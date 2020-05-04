<?php

namespace Osm\Core;

use Osm\Core\Classes\Classes;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Exceptions\PropertyNotSet;

/**
 * @property Object_ $parent
 * @property bool $modified
 */
class Object_ implements \ArrayAccess
{
    /**
     * @var array
     */
    public $class;

    /**
     * @param array $data
     * @param null $name
     * @param null $parent
     * @return static
     */
    public static function new($data = [], $name = null, $parent = null) {
        global $osm_app; /* @var App $osm_app */

        if ($name !== null) {
            $data['name'] = $name;
        }

        if ($parent !== null) {
            $data['parent'] = $parent;
        }

        $class = static::class;
        if (array_key_exists('class', $data)) {
            if ($data['class']) {
                $class = $data['class'];
            }
            unset($data['class']);
        }
        return $osm_app->createRaw($class, $data);
    }

    /**
     * @param array|object $data
     */
    public function __construct($data = []) {
        global $osm_classes; /* @var Classes $osm_classes */

        $this->class = &$osm_classes->get(get_class($this));
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
    }

    protected function getProperty($property) {
        global $osm_classes; /* @var Classes $osm_classes */

        $class = $this->class;
        while (true) {
            if (isset($class['properties'][$property])) {
                return $class['properties'][$property];
            }

            if (!$class['parent']) {
                return null;
            }
            $class = $osm_classes->get($class['parent']);
        }

        return null;
    }

    public function __get($property) {
        global $osm_app; /* @var App $osm_app */
        global $osm_profiler; /* @var Profiler $osm_profiler */

        // The following check is not needed by the very definition of __get() magic method. However, as of
        // PHP 7.2, __get() may be called twice in complex isset expression like isset($object->property[$key])
        // This check is added to prevent unwanted side-effects of calculating property value twice
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        if ($osm_profiler) $osm_profiler->start("{$this->class['name']}::{$property}", 'getters');
        try {
            if (!($property_ = $this->getProperty($property))) {
                return null;
            }

            $value = isset($property_['default']) && $osm_app->properties
                ? call_user_func([$osm_app->properties, $property_['default']], $this)
                : $this->default($property);

            if (isset($property_['required']) && $value === null) {
                throw new PropertyNotSet("Required property '{$property}' is not assigned " .
                    "in class '" . get_class($this) . "'");
            }

            $this->$property = $value;

            if (isset($property_['part'])) {
                $this->modified();
            }

            return $value;
        }
        finally {
            if ($osm_profiler) $osm_profiler->stop("{$this->class['name']}::{$property}");
        }
    }

    protected function default($property) {
        return null;
    }

    public function __isset($key) {
        try {
            return $this->__get($key) !== null;
        }
        catch (PropertyNotSet $e) {
            return false;
        }
        catch (\Exception $e) {
            if ($key == 'class_names') {
                // class_names property required very early, before compiler loads it
                return false;
            }
            throw $e;
        }
    }

    public function set($data) {
        foreach ($data as $property => $value) {
            $this->$property = $value;
        }
        return $this;
    }

    /**
     * @param string $property
     * @return mixed
     */
    public function unset($property) {
        $result = $this->$property;
        unset($this->$property);
        return $result;
    }

    public function __sleep() {
        $result = [];
        foreach (get_object_vars($this) as $property => $value) {
            if (isset($this->getProperty($property)['part'])) {
                $result[] = $property;
            }
        }

        return $result;
    }

    public function __wakeup() {
        global $osm_classes; /* @var Classes $osm_classes */

        $this->class = &$osm_classes->get(get_class($this));
        foreach (get_object_vars($this) as $property => $value) {
            if (!isset($this->getProperty($property)['part'])) {
                continue;
            }

            if ($value instanceof Object_) {
                $value->parent = $this;
                continue;
            }

            if (is_array($value)) {
                foreach ($value as $item) {
                    if ($item instanceof Object_) {
                        $item->parent = $this;
                        continue;
                    }
                }
            }
        }
    }

    public function modified() {
        $this->modified = true;
        if ($this->parent) {
            $this->parent->modified();
        }
    }

    public function toArray() {
        $result = [];
        foreach (get_object_vars($this) as $property => $value) {
            if (!isset($this->getProperty($property)['part'])) {
                continue;
            }
            $result[$property] = osm_array($value);
        }
        return $result;
    }

    public function toObject() {
        $result = new \stdClass();
        foreach (get_object_vars($this) as $property => $value) {
            if (!isset($this->getProperty($property)['part'])) {
                continue;
            }
            if ($value !== null) {
                $result->$property = osm_object($value);
            }
        }
        return $result;
    }

    /**
     * @return \Generator|Object_[]
     */
    public function iterateParts() {
        foreach (get_object_vars($this) as $property => $value) {
            if (!isset($this->getProperty($property)['part'])) {
                continue;
            }

            if (!is_iterable($value)) {
                yield $property => $value;
                continue;
            }

            foreach ($value as $index => $item) {
                yield "{$property} {$index}" => $item;
            }
        }
    }

    public function offsetExists($offset) { throw new NotSupported(); }
    public function offsetGet($offset) { throw new NotSupported(); }
    public function offsetSet($offset, $value) { throw new NotSupported(); }
    public function offsetUnset($offset) { throw new NotSupported(); }
}