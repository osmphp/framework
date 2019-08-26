<?php

namespace Osm\Framework\Laravel;

use Illuminate\Console\Application;
use Osm\Core\App;

class Console extends Application
{
    public function getName() {
        global $m_app; /* @var App $m_app */

        return $m_app->settings->app_title;
    }

}