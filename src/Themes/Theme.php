<?php

declare(strict_types=1);

namespace Osm\Framework\Themes;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Attributes\Serialized;

/**
 * @property string $name #[Serialized]
 * @property ?string $parent #[Serialized]
 * @property ?bool $dev #[Serialized]
 * @property array $after
 * @property string $gulpfile #[Serialized]
 * @property ?string $area_name
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

    /** @noinspection PhpUnused */
    protected function get_area_name(): ?string {
        return preg_match('/^_([^_]+)/', $this->name, $match)
            ? $match[1]: null;
    }
}