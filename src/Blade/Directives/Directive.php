<?php

declare(strict_types=1);

namespace Osm\Framework\Blade\Directives;

use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $name
 */
class Directive extends Object_
{
    protected function get_name(): string {
        throw new NotImplemented($this);
    }

    public function render(string $expression): string {
        throw new NotImplemented($this);
    }
}