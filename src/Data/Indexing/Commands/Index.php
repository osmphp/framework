<?php

namespace Osm\Data\Indexing\Commands;

use Osm\Core\App;
use Osm\Data\Indexing\Indexing;
use Osm\Data\Indexing\Mode;
use Osm\Framework\Console\Command;

/**
 * @property Indexing $indexing @required
 */
class Index extends Command
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'indexing': return $osm_app[Indexing::class];
        }
        return parent::default($property);
    }

    public function run() {
        $this->indexing->run(
            $this->input->getOption('full') ? Mode::FULL : Mode::PARTIAL,
            $this->input->getOption('group'),
            $this->input->getArgument('target'),
            $this->input->getArgument('source'),
            $this->input->getOption('no-transaction'),
            $this->output
        );
    }
}