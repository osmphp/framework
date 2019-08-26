<?php

namespace Osm\Framework\Cron\Commands;

use Carbon\Carbon;
use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Cron\Job;
use Osm\Framework\Cron\Jobs;

/**
 * @property Carbon $now @required
 * @property Jobs|Job[] $jobs @required
 */
class Process extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'now': return Carbon::now();
            case 'jobs': return $m_app->cron_jobs;

        }
        return parent::default($property);
    }

    public function run() {
        foreach ($this->jobs as $job) {
            if ($job->isScheduledAt($this->now)) {
                $job->run();
            }
        }
    }
}