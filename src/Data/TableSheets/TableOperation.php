<?php

namespace Osm\Data\TableSheets;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Db\Db;
use Osm\Framework\Validation\Exceptions\ValidationFailed;
use Osm\Framework\Validation\Validator;

/**
 * @property TableSheet $parent @required
 * @property Db|TableQuery[] $db @required
 * @property Validator $validator @required
 *
 * @property object|array $values @required
 * @property string|int $criteria
 * @property string $set
 *
 * @property int $count @required
 * @property int $id
 * @property object $valid_values @required
 * @property object $final_values @required
 * @property bool $inserting @required
 */
class TableOperation extends Object_
{
    #region Properties
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
            case 'validator': return $osm_app[Validator::class];

            case 'inserting': return $this->criteria === null;
            case 'valid_values': return $this->validator->validate(
                (object)$this->values, $this->parent->row_class,
                ['strict' => $this->inserting]
            );
            case 'final_values': return $this->getFinalValues();
            case 'count': return is_string($this->criteria)
                ? $this->query()->value("COUNT(id)")
                : ($this->criteria ? 1 : 0);
            case 'id': return is_string($this->criteria)
                ? $this->query()->first("id")
                : ($this->criteria ?: null);
        }
        return parent::default($property);
    }

    protected function query() {
        $query = $this->parent->query($this->set);

        return is_string($this->criteria)
            ? $query->where($this->criteria)
            : $query->where("id = ?", $this->criteria);
    }

    protected function getFinalValues() {
        return $this->valid_values;
    }
    #endregion

    public function insert() {
        return $this->parent->query()->insert((array)$this->final_values);
    }

    public function update() {
        $this->query()->update((array)$this->final_values);
    }

    public function delete() {
        $this->query()->delete();
    }

    protected function validateUnique($column, $message) {
        if (!isset($this->valid_values->$column)) {
            return;
        }

        $id = $this->db['customers']
            ->where("$column = ?", $this->valid_values->$column)
            ->value("id");

        if (!$id) {
            return;
        }

        if ($this->count > 1) {
            throw new ValidationFailed(([$column =>
                (string)osm_t("Can't assign unique :value to more than one row",
                ['value' => $this->valid_values->$column])]));
        }

        if ($id != $this->id) {
            throw new ValidationFailed([$column => (string)$message]);
        }
    }
}