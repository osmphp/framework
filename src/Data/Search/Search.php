<?php

namespace Osm\Data\Search;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\Sheets\Sheet;
use Osm\Framework\Data\Traits\CloneableTrait;

/**
 * @property string $sheet @required @part
 * @property Sheet $sheet_ @required
 *
 * @property string $for @part
 * @property int $limit @part
 * @property int $offset @part
 */
abstract class Search extends Object_
{
    use CloneableTrait;

    const FOR_DISPLAY = 'for_display';

    /**
     * @var string[] @required @part
     */
    public $columns = [];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'sheet_': return $m_app->sheets[$this->sheet];
        }
        return parent::default($property);
    }

    /**
     * @return SearchResult
     */
    abstract public function get();

    /**
     * @param string[] ...$columns
     * @return Search
     */
    public function select(...$columns) {
        $this->registerMethodCall(__FUNCTION__, ...$columns);

        foreach ($columns as $column) {
            $this->columns[$column] = $column;
        }

        return $this;
    }

    public function limit($limit) {
        $this->registerMethodCall(__FUNCTION__, $limit);

        $this->limit = $limit;

        return $this;
    }

    public function offset($offset) {
        $this->registerMethodCall(__FUNCTION__, $offset);

        $this->offset = $offset;

        return $this;
    }

    public function forDisplay() {
        $this->for = static::FOR_DISPLAY;
    }

    protected function getColumnDefinition($name) {
        return $this->sheet_->columns_[$name];
    }
}