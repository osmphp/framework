<?php

namespace Osm\Data\Indexing;

use Illuminate\Console\OutputStyle;
use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Db\Db;

/**
 * @property string $name @required @part
 * @property string $title @required @part
 * @property array $indexers @required @part
 * @property bool $no_transaction @part If set, target <= source update will run without database transaction
 * @property Indexer[] $indexers_ @required @part
 * @property Db $db @required
 * @property Scope $scope @temp
 */
class Target extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'indexers_': return $this->getIndexers();
            case 'db': return $m_app->db;
        }
        return parent::default($property);
    }

    public function index() {
        if (empty($this->indexers)) {
            return;
        }

        if ($this->no_transaction || $this->scope->no_transaction) {
            $this->doIndex();
        }
        else {
            $this->db->connection->transaction(function() {
                $this->doIndex();
            });
        }
    }

    protected function doIndex() {
        foreach ($this->indexers_ as $indexer) {
            $indexer->scope = $this->scope;
            $indexer->index();
        }

        $this->db->connection->table('indexers')
            ->where('target', '=', $this->name)
            ->update(['requires_partial_reindex' => false, 'requires_full_reindex' => false]);
    }

    protected function getIndexers() {
        $result = [];

        foreach ($this->indexers as $name => $data) {
            $result[$name] = Indexer::new($data, $name, $this);
        }

        return $result;
    }
}