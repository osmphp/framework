<?php

namespace Osm\Framework\Cron\Commands;

use Carbon\Carbon;
use Osm\Core\App;
use Osm\Framework\Console\Command;
use Osm\Framework\Cron\Job;
use Osm\Framework\Cron\Jobs;
use Psr\Log\LoggerInterface;

/**
 * @property Carbon $now @required
 * @property Jobs|Job[] $jobs @required
 * @property LoggerInterface $log @required
 */
class Process extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'now': return Carbon::now();
            case 'jobs': return $osm_app->cron_jobs;
            case 'log': return $osm_app->logs->cron;
        }
        return parent::default($property);
    }

    public function run() {
        foreach ($this->jobs as $job) {
            if ($job->isScheduledAt($this->now)) {
                $startedAt = microtime(true);
                try {
                    $job->run();

                    $elapsed = round((microtime(true) -
                        $startedAt) * 1000);
                    $this->log->notice("{$job->name}: {$elapsed}ms");
                }
                catch (\Throwable $e) {
                    $this->log->critical("{$job->name} FAILED");
                    throw $e;
                }
            }
        }
    }
}