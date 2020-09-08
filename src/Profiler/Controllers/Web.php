<?php

namespace Osm\Framework\Profiler\Controllers;

use Osm\Core\App;
use Osm\Core\Profiler;
use Osm\Framework\Http\Controller;
use Osm\Framework\Http\Exceptions\NotFound;
use Osm\Framework\Profiler\Metrics;
use Osm\Framework\Profiler\Tag;

/**
 * @property Metrics $metrics @required
 * @property int $timer_width @required
 * @property int $total_width @required
 * @property int $count_width @required
 * @property int $average_width @required
 * @property int $width @required
 * @property string $header @required
 * @property string $tag @required
 * @property string $timer @required
 * @property string $continuation @required
 * @property string $delimiter @required
 * @property float $tag_limit @required
 * @property float $timer_limit @required
 * @property float $min @required
 */
class Web extends Controller
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */
        switch ($property) {
            case 'metrics': return Metrics::new(['id' => $osm_app->query['id']]);
            case 'timer_width': return 60;
            case 'total_width': return 15;
            case 'count_width': return 10;
            case 'average_width': return 15;
            case 'width': return $this->timer_width + $this->total_width + $this->count_width + $this->average_width;
            case 'header':
                return "%-{$this->timer_width}s%{$this->total_width}s%{$this->count_width}s%{$this->average_width}s\n";
            case 'tag':
                return "%-{$this->timer_width}s%{$this->total_width}.1f\n";
            case 'timer':
                return "%-{$this->timer_width}s%{$this->total_width}.1f%{$this->count_width}d%{$this->average_width}.1f\n";
            case 'continuation':
                return "%-{$this->timer_width}s\n";
            case 'delimiter': return str_repeat('-', $this->width) . "\n";
            case 'tag_limit': return 1.0;
            case 'timer_limit': return 1.0;
            case 'min': return 0.1; // ms
        }
        return parent::default($property);
    }

    public function plainTextPage() {
        global $osm_profiler; /* @var Profiler $osm_profiler */

        if (!$osm_profiler) {
            // if profiler is disabled, profile page doesn't exist
            throw new NotFound(osm_t("Page not found"));
        }

        return
            sprintf($this->header, osm_t("Timer"), osm_t("Total (ms)"), osm_t("Count"), osm_t("Avg (ms)")) .
            $this->delimiter .
            sprintf($this->tag, (string)osm_t("TOTAL"), $this->metrics->total) .
            $this->plainTextTags();
    }

    protected function plainTextTags() {
        $result = '';
        $total = 0.0;
        $other = 0.0;
        foreach ($this->metrics->tags as $tag) {
            if ($tag->total < $this->min) {
                continue;
            }
            if ($total < $this->tag_limit * $this->metrics->total) {
                $result .=
                    $this->delimiter .
                    sprintf($this->tag, $tag->name, $tag->total) .
                    $this->plainTextTimers($tag);

            }
            else {
                $other += $tag->total;
            }

            $total += $tag->total;
        }

        if ($other > 0.0) {
                $result .=
                    $this->delimiter .
                    sprintf($this->tag, osm_t("Other tags"), $other);
        }

        return $result;
    }

    protected function plainTextTimers(Tag $tag) {
        $result = '';
        if (!count($tag->timers)) {
            return $result;
        }

        $result .= $this->delimiter;
        $total = 0.0;
        $other = 0.0;
        foreach ($tag->timers as $timer) {
            if ($timer->total < $this->min) {
                continue;
            }

            if ($total < $this->timer_limit * $tag->total) {
                $name = str_replace("\n", ' ', $timer->name);
                if (mb_strlen($name) > $this->timer_width) {
                    $result .= sprintf($this->timer, mb_substr($name, 0, $this->timer_width),
                        $timer->total, $timer->count, $timer->average);
                    $name = mb_substr($name, $this->timer_width);
                    while (mb_strlen($name) > 0) {
                        $result .= sprintf($this->continuation, mb_substr($name, 0, $this->timer_width));
                        $name = mb_substr($name, $this->timer_width);
                    }
                }
                else {
                    $result .= sprintf($this->timer, $name, $timer->total, $timer->count, $timer->average);
                }
            }
            else {
                $other += $timer->total;
            }

            $total += $timer->total;
        }

        if ($other > 0.0) {
                $result .= sprintf($this->tag, osm_t("Other timers"), $other);
        }

        return $result;
    }

}