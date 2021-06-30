<?php

declare(strict_types=1);

namespace Osm {
    use Osm\Core\App;

    function asset($filename): string {
        global $osm_app; /* @var App $osm_app */

        return "{$osm_app->http->base_url}/{$osm_app->theme->name}/{$filename}";
    }
}