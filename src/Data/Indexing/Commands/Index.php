<?php

namespace Manadev\Data\Indexing\Commands;

use Manadev\Core\App;
use Manadev\Data\Indexing\Indexing;
use Manadev\Data\Indexing\Mode;
use Manadev\Framework\Console\Command;

/**
 * @property Indexing $indexing @required
 */
class Index extends Command
{
    public function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'indexing': return $m_app[Indexing::class];
        }
        return parent::default($property);
    }

    public function run() {
        $this->indexing->run(
            $this->input->getOption('full') ? Mode::FULL : Mode::PARTIAL,
            $this->input->getArgument('target'),
            $this->input->getArgument('source'),
            $this->input->getOption('no-transaction'),
            $this->output
        );
    }
}