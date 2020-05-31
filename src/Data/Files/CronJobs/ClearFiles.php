<?php

namespace Osm\Data\Files\CronJobs;

use Carbon\Carbon;
use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Cron\Job;
use Osm\Framework\Settings\Settings;

/**
 * @property Files $files @required
 * @property Settings $settings @required
 */
class ClearFiles extends Job
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
            case 'settings': return $osm_app->settings;
        }
        return parent::default($property);
    }

    public function isScheduledAt(Carbon $time) {
        if (!$this->settings->collect_file_garbage) {
            return false;
        }

        return parent::isScheduledAt($time);
    }

    public function run() {
        $this->files->gc();
    }
}