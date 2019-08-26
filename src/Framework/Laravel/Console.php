<?php

namespace Osm\Framework\Laravel;

use Illuminate\Console\Application;
use Osm\Core\App;

class Console extends Application
{
    public function getName() {
        global $osm_app; /* @var App $osm_app */

        return $osm_app->settings->app_title;
    }

}