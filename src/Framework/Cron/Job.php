<?php

namespace Osm\Framework\Cron;

use Carbon\Carbon;
use Cron\CronExpression;
use Cron\FieldFactory;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $schedule @required @part
 */
class Job extends Object_
{
    public function isScheduledAt(Carbon $time) {
        return (new CronExpression($this->schedule, new FieldFactory()))->isDue($time);
    }

    public function run() {
        throw new NotImplemented();
    }

    protected function getEveryMinuteSchedule() {
        return '* * * * *';
    }
}