<?php

namespace Manadev\Framework\Db;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\SqlServerConnection;
use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;
use Manadev\Framework\Db\Logging\QueryLoggingClasses;
use Manadev\Framework\Db\Traits\ConnectionFactoryTrait;
use Manadev\Framework\Db\Traits\ConnectionTrait;

/**
 * @property QueryLoggingClasses|string[] $query_logging_classes @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
        ConnectionFactory::class => ConnectionFactoryTrait::class,
        MySqlConnection::class => ConnectionTrait::class,
        PostgresConnection::class => ConnectionTrait::class,
        SqlServerConnection::class => ConnectionTrait::class,
        SQLiteConnection::class => ConnectionTrait::class,
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'query_logging_classes': return $m_app->cache->remember('query_logging_classes',
                function($data) {
                    return QueryLoggingClasses::new($data);
                });
        }
        return parent::default($property);
    }
}