<?php

namespace Osm\Framework\Blade;

use Osm\Core\Exceptions\Required;
use Osm\Core\Object_;

/**
 * Render-time properties:
 *
 * @property string $template
 * @property array $data
 * @property bool $rendering true if the view is created using `view()`
 *      helper function
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