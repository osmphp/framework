<?php

declare(strict_types=1);

namespace Osm\Framework\Samples\Http\Components\Front;

use Osm\Framework\Themes\Blade\Component;

class Layout extends Component
{
    public string $template = 'sample-http::layout';

    public function __construct() {
        $a = 1;
    }
}