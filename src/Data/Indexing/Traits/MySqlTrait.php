<?php

namespace Manadev\Data\Indexing\Traits;

use Illuminate\Database\Schema\Blueprint;
use Manadev\Data\Formulas\Types;
use Manadev\Data\Indexing\Event;
use Manadev\Data\Tables\Columns\Column;
use Manadev\Framework\Db\Db;

trait MySqlTrait
{
    public function createIndexingTriggers($id, $table, $events = [], $columns = []) {
        /* @var Db $db */
        $db = $this;

        $notificationTable = "{$table}__n{$id}";
        $this->createNotificationTable($table, $notificationTable);

        foreach ($events as $event) {
            $event = strtolower($event);
            $this->createIndexingTrigger($id, $table, $notificationTable, $event, $columns);

            if ($event == 'update') {
                foreach ($db->tables[$table]->partitions as $partition) {
                    $this->createIndexingTrigger($id, "{$table}__{$partition}", $notificationTable, $event, $columns);
                }
            }
        }
    }

    protected function createNotificationTable($mainTable, $notificationTable) {
        /* @var Db $db */
        $db = $this;

        $db->schema->create($notificationTable, function(Blueprint $table) use ($mainTable) {
            $table->unsignedInteger('id');
            $table->primary('id');
            $table->foreign('id')->references('id')->on($mainTable)->onDelete('cascade');
        });

        $db->tables->register($notificationTable, [
            'id' => Column::new(['type' => Column::INT_, 'data_type' => Types::INT_], 'id')
                ->partition(1)->unsigned()->title("ID")->pinned(),
        ]);
    }

    protected function createIndexingTrigger($id, $table, $notificationTable, $event, $columns) {
        /* @var Db $db */
        $db = $this;

        $table_ = $db->tables[$table];
        $trigger = "{$table}_{$event}_{$id}_trg";
        $triggerEvents = [Event::INSERT => 'AFTER INSERT', Event::UPDATE => 'AFTER UPDATE',
            Event::DELETE => 'BEFORE DELETE'];
        $idExpressions = [Event::INSERT => 'NEW.`id`', Event::UPDATE => 'NEW.`id`', 'delete' => 'OLD.`id`'];

        $sql = "CREATE TRIGGER `$trigger` {$triggerEvents[$event]} ON `{$table}`\n";
        $sql .= "FOR EACH ROW\n";
        $sql .= "BEGIN\n";

        $condition = "";
        if ($event != 'update' || empty($columns)) {
            $indent = str_repeat(' ', 4);
        }
        else { // $event == Event::UPDATE
            foreach ($columns as $column) {
                if (!isset($table_->columns[$column])) {
                    continue;
                }

                if ($condition) {
                    $condition .= " AND ";
                }

                $condition .= "IF(OLD.`$column` IS NULL, " .
                    "NEW.`$column` IS NULL, " .
                    "OLD.`$column` <> NEW.`$column`)";
            }

            if (!$condition) {
                return;
            }

            $sql .= "    IF ($condition) THEN\n";
            $indent = str_repeat(' ', 8);
        }

        $sql .= "{$indent}INSERT IGNORE INTO {$db->wrapTable($notificationTable)} (`id`) " .
            "VALUES({$idExpressions[$event]});\n";
        $sql .= "{$indent}UPDATE {$db->wrapTable('indexers')} SET `requires_partial_reindex` = 1 " .
            "WHERE `id` = {$id};\n";

        if ($condition) {
            $sql .= "    END IF;\n";
        }

        $sql .= "END\n";

        $db->connection->unprepared($sql);
    }

    public function dropIndexingTriggers($id, $table, $events = []) {
        /* @var Db $db */
        $db = $this;

        $notificationTable = "{$table}__n{$id}";

        foreach ($events as $event) {
            $event = strtolower($event);
            $this->dropIndexingTrigger($id, $table, $event);

            if ($event == 'update') {
                foreach ($db->tables[$table]->partitions as $partition) {
                    $this->dropIndexingTrigger($id, "{$table}__{$partition}", $event);
                }
            }
        }

        $db->schema->drop($notificationTable);
        $db->tables->unregister($notificationTable);
    }

    protected function dropIndexingTrigger($id, $table, $event) {
        /* @var Db $db */
        $db = $this;

        $trigger = "{$table}_{$event}_{$id}_trg";
        $db->connection->unprepared("DROP TRIGGER `$trigger`");
    }
}