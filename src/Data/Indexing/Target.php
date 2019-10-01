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
 * @property Db $db @required
 * @property Scope $scope @temp
 */
class Target extends Object_
{
    #region Properties
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'db': return $osm_app->db;
        }
        return parent::default($property);
    }

    protected function createIndexer($data, $name) {
        return Indexer::new($data, $name, $this);
    }
    #endregion

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
        foreach ($this->indexers as $name => $data) {
            $data['scope'] = $this->scope;
            $this->createIndexer($data, $name)->index();
        }

        $this->db->connection->table('indexers')
            ->where('target', '=', $this->name)
            ->update(['requires_partial_reindex' => false, 'requires_full_reindex' => false]);
    }
}