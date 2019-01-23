<?php

namespace Manadev\Core\Compilation;

use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string[] $traits @required @part
 * @property string $generated_name @required @part
 * @property bool $propagated @part
 *
 * @property string $namespace @required @part
 * @property string $short_name @required @part
 * @property string[] $property_names @required @part
 * @property string[] $method_names @required @part
 * @property Alias[] $aliases @required @part
 * @property Method[] $methods @required @part
 * @property bool $abstract @required @part
 */
class Class_ extends Object_
{
    public function default($property) {
        switch ($property) {
            case 'generated_name':
                return 'Generated\\' . ucfirst(env('APP_ENV')) . '\\' . $this->name;
        }
        return parent::default($property);
    }
}