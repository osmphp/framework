<?php

namespace Manadev\Framework\Cron\Traits;

use Manadev\Core\App;
use Manadev\Framework\Cron\Jobs;

trait PropertiesTrait
{
    public function Manadev_Core_App__cron_jobs(App $app) {
        return $app->cache->remember('cron_jobs', function($data) {
            return Jobs::new($data);
        });
    }

}