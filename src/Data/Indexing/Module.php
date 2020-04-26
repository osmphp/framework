<?php

namespace Osm\Data\Indexing;

use Osm\Core\App;
use Osm\Core\Properties;
use Osm\Data\Indexing\Jobs\Index;
use Osm\Data\Indexing\Traits\MySqlTrait;
use Osm\Data\Indexing\Traits\MigratorTrait;
use Osm\Core\Classes\Classes;
use Osm\Core\Modules\BaseModule;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Db\MySql;
use Osm\Framework\Migrations\Migration;
use Osm\Framework\Migrations\Migrator;
use Osm\Framework\Queues\Queue;
use Osm\Framework\Queues\Queues;

/**
 * @property Indexing $indexing @required
 * @property Queues|Queue[] $queues @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        MySql::class => MySqlTrait::class,
        Migrator::class => MigratorTrait::class,
        TableQuery::class => Traits\TableQueryTrait::class,
    ];

    public $hard_dependencies = [
        'Osm_Framework_Db',
        'Osm_Framework_Migrations',
        'Osm_Framework_Queues',
        'Osm_Data_Tables',
        'Osm_Data_TableQueries',
    ];

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'indexing': return $osm_app[Indexing::class];
            case 'queues': return $osm_app->queues;
        }
        return parent::default($property);
    }

    public function terminate() {
        if ($this->indexing->requiresReindex()) {
            $this->indexing->run();
        }

        foreach ($this->indexing->getModifiedGroups() as $group) {
            $this->queues->dispatch(Index::new(['queue' => $group ?: 'default']));
        }

        parent::terminate();
    }
}