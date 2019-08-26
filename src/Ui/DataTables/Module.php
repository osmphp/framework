<?php

namespace Osm\Ui\DataTables;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property string[] $column_types @required
 */
class Module extends BaseModule
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'column_types': return $m_app->cache->remember('data_table_column_types', function() use ($m_app) {
                return ColumnTypes::new();
            });
        }
        return parent::default($property);
    }
}