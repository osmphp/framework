<?php

namespace Manadev\Framework\Cron\Commands;

use Carbon\Carbon;
use Manadev\Core\App;
use Manadev\Framework\Console\Command;
use Manadev\Framework\Cron\Job;
use Manadev\Framework\Cron\Jobs;

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