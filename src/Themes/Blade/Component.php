<?php

declare(strict_types=1);

namespace Osm\Framework\Themes\Blade;

use Illuminate\View\Component as BaseComponent;
use Osm\Core\App;
use Osm\Core\Class_;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Traits\Reflection;
use Osm\Runtime\Traits\ComputedProperties;
use function Osm\view;

/**
 * @property string $__template
 */
class Component extends BaseComponent
{
    use ComputedProperties, Reflection;

    /** @noinspection PhpMissingReturnTypeInspection */
    public function render() {
        return view($this->__template);
    }

    /** @noinspection PhpUnused */
    protected function get___template(): string {
        throw new NotImplemented();
    }


    /** @noinspection PhpMissingReturnTypeInspection */
    protected function extractPublicProperties() {
        if (! isset(static::$propertyCache[$this->__class->name])) {
            $propertyNames = [];
            foreach ($this->__class->properties as $property) {
                if (!$this->shouldIgnore($property->name)) {
                    $propertyNames[] = $property->name;
                }
            }

            static::$propertyCache[$this->__class->name] = $propertyNames;
        }


        return parent::extractPublicProperties();
    }
}