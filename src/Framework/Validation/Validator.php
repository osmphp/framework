<?php

namespace Manadev\Framework\Validation;

use Manadev\Core\App;
use Manadev\Core\Classes\Classes;
use Manadev\Core\Object_;
use Manadev\Framework\Validation\Exceptions\InvalidConstraint;
use Manadev\Framework\Validation\Exceptions\ValidationFailed;

/**
 * @property Patterns|Pattern[] $patterns @required
 * @property string[] $errors @temp
 */
class Validator extends Object_
{
    const UNKNOWN_CONSTRAINT = 0;
    const TYPE_CONSTRAINT = 1;
    const CLASS_CONSTRAINT = 2;

    protected $type_cache = [];

    protected function default($property) {
        global $m_app;/* @var App $m_app */

        switch ($property) {
            case 'patterns': return $m_app[Patterns::class];
        }

        return parent::default($property);
    }

    public function validate($data, $type, $options = []) {
        $this->errors = [];

        $result = $this->doValidate($data, $this->createProperty($type, $options));

        if (!empty($this->errors)) {
            throw new ValidationFailed(m_("Validation failed"), $this->errors);
        }

        return $result;
    }

    protected function getConstraintType($constraint) {
        if (!isset($this->type_cache[$constraint])) {
            if (method_exists($this, 'validate' . studly_case($constraint))) {
                $this->type_cache[$constraint] = static::TYPE_CONSTRAINT;
            }
            elseif (class_exists($constraint)) {
                $this->type_cache[$constraint] = static::CLASS_CONSTRAINT;
            }
            else {
                $this->type_cache[$constraint] = static::UNKNOWN_CONSTRAINT;
            }
        }

        return $this->type_cache[$constraint];
    }

    protected function createProperty($type, $options) {
        $array = false;
        if (strrpos($type, '[]') === strlen($type) - strlen('[]')) {
            $array = true;
            $type = substr($type, 0, strlen($type) - strlen('[]'));
        }

        if ($type === 'array') {
            $array = true;
            $type = 'mixed';
        }

        $data = ['type' => $type, 'required' => true];
        if ($array) {
            $data['array'] = true;
        }

        return array_merge($data, $options);
    }

    protected function validationFailed($path, $error) {
        $this->errors[ltrim($path, '/')] = (string)$error;
        return null;
    }

    protected function doValidate($data, array $property, $path = '') {
        if (!empty($property['array'])) {
            return $this->validateArray($data, $property, $path);
        }

        if ($data === null) {
            if (!empty($property['required'])) {
                return $this->validationFailed($path, $path
                    ? m_("Fill in this field")
                    : m_("Data expected"));
            }
            return $data;
        }

        return $this->validateValue($data, $property, $path);
    }

    protected function validateArray($data, array $property, $path) {
        if (!is_array($data)) {
            return $this->validationFailed($path, m_("Array expected"));
        }

        $intKeys = array_first(array_keys($data)) === 0;
        $unset = false;

        foreach ($data as $key => $value) {
            if ($value === null) {
                unset($data[$key]);
                $unset = true;
            }
            elseif (($value = $this->validateValue($value, $property,  "$path/$key")) !== null) {
                $data[$key] = $value;
            }
            else {
                unset($data[$key]);
                $unset = true;
            }
        }

        if ($intKeys && $unset) {
            $data = array_values($data);
        }
        return $data;
    }

    protected function validateValue($data, array $property, $path) {
        switch ($this->getConstraintType($property['type'])) {
            case static::TYPE_CONSTRAINT:
                return $this->{'validate' . studly_case($property['type'])}($data, $property, $path);
            case static::CLASS_CONSTRAINT:
                return $this->validateClass($data, $property['type'], $path);
            default:
                throw new InvalidConstraint(m_("Constraint ':constraint' is neither simple type nor class name",
                    ['constraint' => $property['type']]));
        }
    }

    protected function validateClass($data, $class, $path) {
        global $m_classes; /* @var Classes $m_classes */

        if (is_array($data)) {
            if (!empty($data) && is_int(array_first(array_keys($data)))) {
                return $this->validationFailed($path, m_("Object of class ':class' expected", ['class' => $class]));
            }
            $data = (object)$data;
        }

        if (!is_object($data)) {
            return $this->validationFailed($path, m_("Object of class ':class' expected", ['class' => $class]));
        }

        while ($class) {
            $class_ = $m_classes->get($class);
            foreach ($class_['properties'] as $property => $property_) {
                if (($value = $this->doValidate($data->$property ?? null, $property_,
                    "$path/$property")) !== null)
                {
                    $data->$property = $value;
                }
                else {
                    unset($data->$property);
                }
            }
            $class = $class_['parent'];
        }

        return $data;
    }

    protected function validateString($data, array $property, $path) {
        if (!is_string($data)) {
            return $this->validationFailed($path, m_("String expected"));
        }

        $data = trim($data);

        if (!$data && !empty($property['required'])) {
            return $this->validationFailed($path, m_("Fill in this field"));
        }

        if (!empty($property['max_length']) && mb_strlen($data) > $property['max_length']) {
            return $this->validationFailed($path, m_("Value should be no longer than :length characters",
                ['length' => $property['max_length']]));
        }

        if (!empty($property['min_length']) && mb_strlen($data) < $property['min_length']) {
            return $this->validationFailed($path, m_("Value should be at least :length characters long",
                ['length' => $property['min_length']]));
        }

        if (!empty($property['pattern']) && !preg_match($this->patterns[$property['pattern']]->pattern, $data)) {
            return $this->validationFailed($path, $this->patterns[$property['pattern']]->error_message);
        }

        return $data;
    }

    protected function validateInt($data, array $property, $path) {
        if (!is_int($data)) {
            return $this->validationFailed($path, m_("Integer expected"));
        }

        return $data;
    }

    protected function validateBool($data, array $property, $path) {
        if (!is_bool($data)) {
            return $this->validationFailed($path, m_("Boolean expected"));
        }

        return $data;
    }
}