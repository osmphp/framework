<?php

namespace Manadev\Data\Indexing;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Db\Db;

/**
 * @property string $target @required @part
 * @property Db $db @required
 */
class FluentIndexerDefinition extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'db': return $m_app->db;
        }
        return parent::default($property);
    }

    public function source($source, $events = [], $columns = []) {
        $id = $this->db->connection->table('indexers')->insertGetId([
            'target' => $this->target,
            'source' => $source,
            'events' => implode(',', $events),
            'columns' => implode(',', $columns),
        ]);

        if (!empty($events)) {
            $this->db->createIndexingTriggers($id, $source, $events, $columns);
        }

        return $this;
    }
}