<?php

declare(strict_types=1);

namespace Osm {

    use Illuminate\Contracts\View\View;
    use Osm\Core\App;

    function view(string $template, array $data = [], array $mergeData = [])
        : View
    {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->theme->views->make($template, $data, $mergeData);
    }
}