<?php

namespace Manadev\Data\Indexing;

use Manadev\Core\App;
use Manadev\Core\Properties;
use Manadev\Data\Indexing\Traits\MySqlTrait;
use Manadev\Data\Indexing\Traits\MigratorTrait;
use Manadev\Core\Classes\Classes;
use Manadev\Core\Modules\BaseModule;
use Manadev\Framework\Db\MySql;
use Manadev\Framework\Migrations\Migration;
use Manadev\Framework\Migrations\Migrator;

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
        'Manadev_Framework_Db',
        'Manadev_Framework_Migrations',
        'Manadev_Data_Tables',
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