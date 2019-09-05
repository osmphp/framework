<?php

namespace Osm\Framework\Queues;

use Osm\Core\App;
use Osm\Core\Object_;
use Illuminate\Queue\Queue as LaravelQueue;

/**
 * @property LaravelQueue $laravel_queue @required
 * @property string $default @required @part Default queue name
 * @property int $retry_after @required @part
 */
class Queue extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'default': return 'default';
            case 'retry_after': return 60;
        }
        return parent::default($property);
    }
}