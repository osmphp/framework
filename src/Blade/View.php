<?php

namespace Osm\Framework\Blade;

use Osm\Core\Attributes\Serialized;
use Osm\Core\Exceptions\Required;
use Osm\Core\Object_;

/**
 * @property string $template #[Serialized]
 *
 * Render-time properties:
 *
 * @property array $data
 *
 * @uses Serialized
 */
class View extends Object_
{
    protected function get_template(): string {
        throw new Required(__METHOD__);
    }

    protected function get_data(): array {
        throw new Required(__METHOD__);
    }
}