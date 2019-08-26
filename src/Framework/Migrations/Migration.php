<?php

namespace Osm\Framework\Migrations;

use Osm\Data\Indexing\Indexing;
use Osm\Data\Sheets\Query;
use Osm\Data\Sheets\Sheets;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;
use Illuminate\Database\Schema;

/**
 * @property Db $db @required
 * @property Schema\Builder $schema
 *
 * @see \Osm\Data\Indexing\Module:
 *      @property Indexing $indexing @required @default
 * @see \Osm\Data\Sheets\Module:
 *      @property Sheets|Query[] $sheets @required @default
 */
class Migration extends Object_
{
    public function default($property) {
        switch ($property) {
            case 'schema': return $this->db->schema;

        }
        return parent::default($property);
    }

    public function up() {
    }

    public function down() {
    }
}