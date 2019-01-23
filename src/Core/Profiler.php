<?php

namespace Manadev\Core;

use Manadev\Core\Exceptions\ProfilingException;

class Profiler
{
    protected $timers = [];
    protected $metrics = [];
    protected $terminator;
    protected $id;

    public function setTerminator(callable $value) {
        $this->terminator = $value;
    }

    public function getId() {
        if ($this->id === null) {
            $this->id = md5(uniqid('', true));
        }

        return $this->id;
    }

    public function getMetrics() {
        return $this->metrics;
    }

    public function start($timer, $tag) {
        array_push($this->timers, [
            'name' => $timer, 'tag' => $tag,
            'cumulative' => 0.0,
            'started_at' => $startedAt = microtime(true),
        ]);

        return $startedAt;
    }

    public function stop($timer) {
        $timer_ = array_pop($this->timers);
        if ($timer_['name'] != $timer) {
            throw new ProfilingException("Trying to stop time '{$timer}' while measuring timer '{$timer_['name']}'");
        }

        $this->record($timer, $timer_['tag'],
            $elapsed = $this->elapsed($timer_['started_at']) - $timer_['cumulative']);

        return $elapsed;
    }

    public function record($timer, $tag, $elapsed) {
        foreach ($this->timers as &$timer_) {
            $timer_['cumulative'] += $elapsed;
        }

        if (!isset($this->metrics[$timer])) {
            $this->metrics[$timer] = ['tag' => $tag, 'elapsed' => []];
        }
        $this->metrics[$timer]['elapsed'][] = $elapsed;
    }

    public function elapsed($startedAt, $finishedAt = null) {
        if ($finishedAt === null) {
            $finishedAt = microtime(true);
        }
        return ($finishedAt - $startedAt) * 1000;
    }

    public function terminate() {
        if ($this->terminator) {
            call_user_func($this->terminator);
        }
    }
}