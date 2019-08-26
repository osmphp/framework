<?php

namespace Osm\Data\Indexing;

use Osm\Core\App;
use Osm\Core\Properties;
use Osm\Data\Indexing\Traits\MySqlTrait;
use Osm\Data\Indexing\Traits\MigratorTrait;
use Osm\Core\Classes\Classes;
use Osm\Core\Modules\BaseModule;
use Osm\Framework\Db\MySql;
use Osm\Framework\Migrations\Migration;
use Osm\Framework\Migrations\Migrator;

/**
 * @property Indexing $indexing @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        MySql::class => MySqlTrait::class,
        Migrator::class => MigratorTrait::class,
    ];

    public $hard_dependencies = [
        'Osm_Framework_Db',
        'Osm_Framework_Migrations',
        'Osm_Data_Tables',
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'indexing': return $m_app[Indexing::class];
        }
        return parent::default($property);
    }

    public function terminate() {
        if (!$this->indexing->run_async && $this->indexing->requires_reindex) {
            $this->indexing->run();
        }
        parent::terminate();
    }
}