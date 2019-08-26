<?php

namespace Osm\Framework\Cron\Traits;

use Osm\Core\App;
use Osm\Framework\Cron\Jobs;

trait PropertiesTrait
{
    public function Osm_Core_App__cron_jobs(App $app) {
        return $app->cache->remember('cron_jobs', function($data) {
            return Jobs::new($data);
        });
    }

}