<?php

namespace Osm\Framework\Profiler;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Http\Exceptions\NotFound;

/**
 * @property string $id @required
 * @property string $filename @required
 * @property array $data @required
 * @property float $total @required
 * @property Tag[] $tags @required
 */
class Metrics extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'filename': return $m_app->path("{$m_app->temp_path}/profiler/{$this->id}.ser");
            case 'data':
                if (!file_exists($this->filename)) {
                    throw new NotFound(m_("Profile ':id' no longer exists", ['id' => $this->id]));
                }
                return unserialize(file_get_contents($this->filename));
            case 'total': return $this->data['total']['elapsed'][0];
            case 'tags': return $this->getTags();
        }
        return parent::default($property);
    }

    protected function getTags() {
        $result = [];
        $total = 0.0;
        foreach ($this->data as $timer => $data) {
            if ($timer == 'total') {
                continue;
            }

            /* @var Tag $tag */
            if (!isset($result[$data['tag']])) {
                $result[$data['tag']] = Tag::new(['name' => $data['tag'], 'total' => 0.0]);
            }
            $tag = $result[$data['tag']];

            $tag->timers[$timer] = $timer_ = Timer::new(['name' => $timer, 'total' => array_sum($data['elapsed']),
                'count' => count($data['elapsed'])]);
            $timer_->average = $timer_->total / $timer_->count;

            $tag->total += $timer_->total;
            $total += $timer_->total;
        }

        if ($total < $this->total) {
            $name = (string)m_("Not covered");
            $result[$name] = Tag::new(['name' => $name, 'total' => $this->total - $total]);
        }

        $this->sort($result);
        foreach ($result as $tag) {
            $this->sort($tag->timers);
        }
        return $result;
    }

    /**
     * @param Tag[]|Timer[] $timers
     */
    protected function sort(&$timers) {
        uasort($timers, function($a, $b) {
            return $b->total - $a->total;
        });
    }
}