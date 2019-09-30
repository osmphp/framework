<?php

namespace Osm\Data\Indexing\Traits;

use Illuminate\Database\Schema\Blueprint;
use Osm\Data\Formulas\Types;
use Osm\Data\Indexing\Event;
use Osm\Data\Tables\Columns\Column;
use Osm\Framework\Db\Db;

trait MySqlTrait
{
    public function createIndexingTriggers($id, $table, $events = [], $columns = []) {
        /* @var Db $db */
        $db = $this;

        $this->createNotificationTables($id, $table, $events);
        foreach ($events as $event) {
            $event = strtolower($event);
            $notificationTable = $this->getNotificationTableName($id, $table, $event);
            $this->createIndexingTrigger($id, $table, $notificationTable, $event, $columns);

            if ($event == 'update') {
                foreach ($db->tables[$table]->partitions as $partition) {
                    $this->createIndexingTrigger($id, "{$table}__{$partition}", $notificationTable, $event, $columns);
                }
            }
        }
    }

    protected function createNotificationTables($id, $mainTable, $events)
    {
        /* @var Db $db */
        $db = $this;

        $tables = [];
        foreach ($events as $event) {
            $notificationTable = $this->getNotificationTableName($id,
                $mainTable, $event);
            if (isset($tables[$notificationTable])) {
                continue;
            }

            $db->schema->create($notificationTable, function(Blueprint $table) use ($mainTable, $event) {
                $table->unsignedInteger('id');
                $table->primary('id');
                if ($event != Event::DELETE) {
                    $table->foreign('id')->references('id')->on($mainTable)->onDelete('cascade');
                }
            });

            $db->tables->register($notificationTable, [
                'id' => Column::new(['type' => Column::INT_, 'data_type' => Types::INT_], 'id')
                    ->partition(1)->unsigned()->title("ID")->pinned(),
            ]);

            $tables[$notificationTable] = true;
        }
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

        $tables = [];
        foreach ($events as $event) {
            $event = strtolower($event);
            $this->dropIndexingTrigger($id, $table, $event);

            if ($event == 'update') {
                foreach ($db->tables[$table]->partitions as $partition) {
                    $this->dropIndexingTrigger($id, "{$table}__{$partition}", $event);
                }
            }

            $notificationTable = $this->getNotificationTableName($id, $table, $event);
            if (!isset($tables[$notificationTable])) {
                $db->schema->drop($notificationTable);
                $db->tables->unregister($notificationTable);
                $tables[$notificationTable] = true;
            }
        }
    }

    protected function dropIndexingTrigger($id, $table, $event) {
        /* @var Db $db */
        $db = $this;

        $trigger = "{$table}_{$event}_{$id}_trg";
        $db->connection->unprepared("DROP TRIGGER `$trigger`");
    }

    protected function getNotificationTableName($id, $table, $event) {
        return $event != Event::DELETE ? "{$table}__n{$id}" : "{$table}__d{$id}";
    }
}