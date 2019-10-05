<?php

namespace Osm\Data\Indexing\Jobs;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;
use Osm\Data\Indexing\Mode;
use Osm\Framework\Queues\Job;

/**
 * @property string $group
 * @property Indexing $indexing @required
 */
class Index extends Job
{
    public $singleton = true;

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'group': return $this->queue != 'default' ? $this->queue : null;
            case 'key': return $this->group ?: '';
            case 'indexing': return $osm_app[Indexing::class];
        }
        return parent::default($property);
    }

    public function handle() {
        $this->indexing->run(Mode::PARTIAL, $this->group);
    }
}