<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Http\Components\Front;

use Osm\Framework\Blade\Component;

/**
 * @property string $class
 */
class Layout extends Component
{
    public string $__template = 'sample-http::layout';

    public function __construct() {
        $a = 1;
    }

    protected function get_class(): string {
        return 'test';
    }
}