<?php

declare(strict_types=1);

namespace Osm\Framework\Themes;

use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $name #[Serialized]
 * @property ?string $parent #[Serialized]
 * @property ?bool $dev #[Serialized]
 * @property array $after
 */
class Theme extends Object_
{
    /**
     * @var string[]
     */
    #[Serialized]
    public array $paths = [];

    /** @noinspection PhpUnused */
    protected function get_after(): array {
        return $this->parent ? [$this->parent] : [];
    }

    public function toJson(): \stdClass {
        $json = new \stdClass();

        foreach ($this->__class->properties as $property) {
            if (!isset($property->attributes[Serialized::class])) {
                continue;
            }

            if (($value = $this->{$property->name}) === null) {
                continue;
            }

            $json->{$property->name} = $value;
        }

        return $json;
    }
}