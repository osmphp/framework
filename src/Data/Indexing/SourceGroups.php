<?php

namespace Osm\Data\Indexing;

use Osm\Core\App;
use Osm\Data\Indexing\Hints\SourceHint;
use Osm\Framework\Data\CollectionRegistry;
use Osm\Framework\Db\Db;

/**
 * @property Db $db @required
 */
class SourceGroups extends CollectionRegistry
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
        }
        return parent::default($property);
    }

    protected function get() {
        $result = [];

        $records = $this->db->connection->table('indexers')
            ->get(['source', 'group']);

        foreach ($records as $data) {
            /* @var SourceHint $data */
            if (!isset($result[$data->source])) {
                $result[$data->source] = [];
            }

            if (!in_array($data->group, $result[$data->source])) {
                $result[$data->source][] = $data->group;
            }
        }

        $this->modified();

        return $result;
    }
}