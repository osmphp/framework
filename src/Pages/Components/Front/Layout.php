<?php

declare(strict_types=1);

namespace Osm\Framework\Pages\Components\Front;

use Osm\Core\App;
use Osm\Framework\Blade\Component;

class Layout extends Component
{
    public string $__template = 'std-pages::layout';

    public function __construct(public string $title,
        public ?string $description = null)
    {
    }

    public function asset($filename): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->http->base_url}/{$osm_app->theme->name}/{$filename}";
    }
}