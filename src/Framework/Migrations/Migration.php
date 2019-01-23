<?php

namespace Manadev\Framework\Migrations;

use Manadev\Data\Indexing\Indexing;
use Manadev\Data\Sheets\Query;
use Manadev\Data\Sheets\Sheets;
use Manadev\Core\Object_;
use Manadev\Framework\Db\Db;
use Illuminate\Database\Schema;

/**
 * @property Db $db @required
 * @property Schema\Builder $schema
 *
 * @see \Manadev\Data\Indexing\Module:
 *      @property Indexing $indexing @required @default
 * @see \Manadev\Data\Sheets\Module:
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