<?php

namespace Osm\Framework\Db;

use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Database\SqlServerConnection;
use Osm\Core\App;
use Osm\Core\Modules\BaseModule;
use Osm\Core\Properties;
use Osm\Framework\Db\Logging\QueryLoggingClasses;
use Osm\Framework\Db\Traits\ConnectionFactoryTrait;
use Osm\Framework\Db\Traits\ConnectionTrait;

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