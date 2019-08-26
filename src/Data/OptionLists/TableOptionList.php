<?php

namespace Osm\Data\OptionLists;

use Illuminate\Support\Collection;
use Osm\Core\App;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Db\Db;

/**
 * @property Db|TableQuery[] $db
 * @property string $table @required @part
 */
class TableOptionList extends OptionList
{
    public $supports_db_queries = true;

    /**
     * @required @part
     * @var string
     */
    public $key = "id";
    /**
     * @required @part
     * @var string[]
     */
    public $data = ['title' => "title"];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'db': return $m_app->db;
        }
        return parent::default($property);
    }

    protected function all() {
        return $this->query()->get();
    }

    /**
     * @return TableQuery
     */
    protected function query() {
        $query = $this->db[$this->table]->select("{$this->key} AS value");
        foreach ($this->data as $column => $expr) {
            $query->select("{$expr} AS {$column}");
        }

        return $query;
    }

    /**
     * Adds option data to given table query.
     *
     * @param TableQuery $query
     * @param string $key $key column in table query result items should contain option key.
     *      If $key argument is omitted, table query result items are expected to have 'value'
     *      column containing option key.
     * @param null $data In most cases, option data is just 'title', but some option lists
     *      may contain more data columns (for instance, SEO URL keys). Optional $data
     *      argument specifies which data columns should be added to table query using which names.
     *      If $dataMappings argument is omitted, all data columns are added under their original names,
     *      that is, for most option lists, 'title' column is added containing option title
     */
    public function addToQuery($query, $key = 'value', $data = null) {
        $table = "{$key}__option_list";

        if (!isset($query->tables[$table])) {
            $query->leftJoin("{$this->table} AS {$table}",
                "{$table}.{$this->key} = {$key}");
        }

        foreach ($this->data as $property => $column) {
            if ($data) {
                if (!isset($data[$property])) {
                    continue;
                }
                $property = $data[$property];
            }

            $query->select("{$table}.{$column} AS $property");
        }
    }

    protected function collectionLookup(Collection $keys) {
        $questionMarks = implode(', ', str_split(str_repeat('?', count($keys))));

        return $this->query()
            ->where("{$this->key} IN ({$questionMarks})", ...$keys)
            ->get()
            ->keyBy('value');
    }
}