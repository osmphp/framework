<?php

namespace Osm\Framework\Cron;

use Carbon\Carbon;
use Cron\CronExpression;
use Cron\FieldFactory;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $schedule @required @part
 * @property string $schedule_ @required @part
 */
class Job extends Object_
{
    protected function default($property) {
        switch ($property) {
            case 'schedule_': return $this->{'get' . studly_case($this->schedule) . 'Schedule'}();
        }

        return parent::default($property);
    }

    public function isScheduledAt(Carbon $time) {
        return (new CronExpression($this->schedule_, new FieldFactory()))->isDue($time);
    }

    public function run() {
        throw new NotImplemented();
    }

    protected function getEveryMinuteSchedule() {
        return '* * * * *';
    }
}