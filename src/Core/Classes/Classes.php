<?php

namespace Manadev\Core\Classes;

class Classes
{
    const CLASS_COMMENT_PATTERN = '/\s+\*\s+@property\s+(?<type>[A-Za-z0-9_|\\\\[\]]+)\s+\$(?<name>[A-Za-z0-9_]+)(?:\s+(?<description>.*))?/';
    const ATTRIBUTE_PATTERN = '/@(?<attribute>[A-Za-z0-9_]+)(?<has>\((?<value>[^\)]*)\))?/';
    const PROPERTY_TYPE_PATTERN = '/\s+\*\s+@var\s+(?<type>[A-Za-z0-9_|\\\\[\]]+)/';

    /**
     * @var array
     */
    public $items = [];
    public $modified = false;

    /**
     * @param string $name
     * @return array
     */
    public function &get($name) {
        if (!isset($this->items[$name])) {
            $this->items[$name] = $this->fetch($name);
            $this->modified = true;
        }

        return $this->items[$name];
    }

    /**
     * @param string $name
     * @return array
     */
    protected function fetch($name) {
        $reflection = new \ReflectionClass($name);
        $result = [
            'name' => $name,
            'properties' => [],
            'parent' => ($parent = $reflection->getParentClass()) ? $parent->getName() : null,
            'hint' => (bool)preg_match('/\\\\Hints\\\\[a-zA-Z0-9_]*Hint$/', $name),
            'direct_hints' => [],
        ];

        $this->getActualProperties($result, $reflection);
        $this->getDocCommentProperties($result, $reflection);
        $this->mergeParentProperties($result, $result['parent']);

        return $result;
    }

    protected function getActualProperties(&$class, \ReflectionClass $reflection) {
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $propertyReflection) {
            if ($propertyReflection->isStatic()) {
                continue;
            }

            if ($propertyReflection->getDeclaringClass() != $reflection) {
                continue;
            }

            $name = $propertyReflection->getName();
            $type = 'mixed';
            $attributes = [];

            if ($comment = $propertyReflection->getDocComment()) {
                foreach (explode("\n", $comment) as $line) {
                    if (preg_match(static::PROPERTY_TYPE_PATTERN, $line, $matches)) {
                        $type = $this->getPropertyType($matches['type'], $array);
                        if ($array) $attributes['array'] = true;
                    }
                    else {
                        $attributes = array_merge($attributes, $this->getAttributes($line));
                    }
                }
            }

            $this->addProperty($class, $name, $type, $attributes);
        }
    }

    protected function getDocCommentProperties(&$class, \ReflectionClass $reflection) {
        if (!($comment = $reflection->getDocComment())) {
            return;
        }

        foreach (explode("\n", $comment) as $line) {
            if (!preg_match(static::CLASS_COMMENT_PATTERN, $line, $matches)) {
                continue;
            }

            $name = $matches['name'];
            $type = $this->getPropertyType($matches['type'], $array);
            $attributes = array_merge(compact('array'), $this->getAttributes($matches['description'] ?? ''));

            $this->addProperty($class, $name, $type, $attributes);
        }
    }

    /**
     * @param string $type
     * @param bool $array
     * @return string
     */
    protected function getPropertyType($type, &$array) {
        if (($pos = strpos($type, '|')) !== false) {
            // first listed type is considered "main" type, all others are for type inference in IDE
            $type = substr($type, 0, $pos);
        }

        $array = false;
        if (strrpos($type, '[]') === strlen($type) - strlen('[]')) {
            $array = true;
            $type = substr($type, 0, strlen($type) - strlen('[]'));
        }

        if ($type === 'array') {
            $array = true;
            return 'mixed';
        }

        return $type;
    }

    protected function getAttributes($text) {
        $result = [];

        if (!preg_match_all(static::ATTRIBUTE_PATTERN, $text, $matches)) {
            return $result;
        }

        foreach ($matches['attribute'] as $index => $attribute) {
            if (!$matches['has'][$index]) {
                $result[$attribute] = true;
                continue;
            }

            $value = $matches['value'][$index];

            if ($value == 'true') {
                $result[$attribute] = true;
                continue;
            }

            if ($value == 'false') {
                $result[$attribute] = false;
                continue;
            }

            if (starts_with($value, '"') && ends_with($value, '"')) {
                $result[$attribute] = mb_substr($value, 1, mb_strlen($value) - 2);
                continue;
            }

            $result[$attribute] = intval($value);
        }

        return $result;
    }

    protected function addProperty(&$class, $name, $type, array $attributes) {
        if (isset($attributes['default']) && $attributes['default'] === true) {
            $attributes['default'] = strtr($class['name'], '\\', '_') . "__{$name}";
        }

        if (isset($class['properties'][$name])) {
            if ($type != 'mixed') {
                $class['properties'][$name]['type'] = $type;
            }
            $class['properties'][$name] = array_merge($class['properties'][$name], $attributes);
        }
        else {
            $class['properties'][$name] = array_merge(compact('name', 'type'), $attributes);
        }
    }

    protected function mergeParentProperties(&$class, $parent) {
        while ($parent) {
            $parent_ = $this->get($parent);

            foreach ($class['properties'] as $property => &$property_) {
                if (isset($parent_['properties'][$property])) {
                    $property_ = array_merge($parent_['properties'][$property], $property_);
                }
            }

            $parent = $parent_['parent'];
        }

    }
}