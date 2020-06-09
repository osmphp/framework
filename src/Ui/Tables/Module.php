<?php

namespace Osm\Ui\Tables;

use Osm\Core\App;
use Osm\Core\Modules\BaseModule;

/**
 * @property string[] $column_types @required
 */
class Module extends BaseModule
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'column_types': return $osm_app->cache->remember('data_table_column_types', function() use ($osm_app) {
                return ColumnTypes::new();
            });
        }
        return parent::default($property);
    }
}