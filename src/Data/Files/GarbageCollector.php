<?php

namespace Osm\Data\Files;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas as AreaRegistry;
use Osm\Framework\Db\Db;

/**
 * Constructor arguments:
 *
 * @property bool $dry_run
 * @property bool $full
 * @property callable $callback
 *
 * Dependencies:
 *
 * @property Files $files @required
 * @property AreaRegistry|Area[] $areas @required
 * @property Db|TableQuery[] $db @required
 */
class GarbageCollector extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
            case 'areas': return $osm_app->areas;
            case 'db': return $osm_app->db;
        }

        return parent::default($property);
    }

    public function collect() {
        $this->collectUnreferencedFiles();
        $this->collectExpiredSessionFiles();
        $this->collectOrphanFiles();
        $this->collectOrphanRecords();
    }

    protected function collectUnreferencedFiles() {
        if (empty($this->files->reference_columns)) {
            return;
        }

        $where = [];
        foreach ($this->files->reference_columns as $column) {
            $where[] = "$column IS NULL";
        }

        $this->files->delete($where);
    }

    protected function collectExpiredSessionFiles() {
        foreach ($this->areas as $area) {
            if ($area->sessions) {
                // skip areas not having a session cookie
                $this->collectExpiredSessionFilesInArea($area);
            }

        }
    }

    public function collectExpiredSessionFilesInArea(Area $area) {
        if (!$area->sessions) {
            throw new NotSupported("Clearing expired session files " .
                "is only possible while handling a HTTP request in an area " .
                "having a session cookie");
        }

        $ids = $this->db['files']->distinct()->values("session");

        foreach ($ids as $id) {
            if (!$area->sessions[$id]) {
                $this->files->delete("session = '{$id}'");
            }
        }
    }


    protected function collectOrphanFiles() {
        if (!$this->full) {
            return;
        }

        $this->collectOrphanFilesInDirectory(Files::PUBLIC,
            (string)osm_path(Files::PUBLIC));
        $this->collectOrphanFilesInDirectory(Files::PRIVATE,
            (string)osm_path(Files::PRIVATE));
    }

    protected function collectOrphanFilesInDirectory($root, $path) {
        if (!is_dir($path)) {
            return;
        }

        $delete = true;

        foreach (new \DirectoryIterator($path) as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }

            if (mb_strpos($fileInfo->getFilename(), '.') === 0) {
                $delete = false;
                continue;
            }

            $filename = "{$path}/{$fileInfo->getFilename()}";

            if ($fileInfo->isDir()) {
                if (!$this->collectOrphanFilesInDirectory($root, $filename)) {
                    $delete = false;
                }
                continue;
            }

            if (!$this->collectOrphanFile($root, $filename)) {
                $delete = false;
            }
        }

        if ($delete && !$this->dry_run) {
            rmdir($path);
        }

        return $delete;
    }

    protected function collectOrphanFile($root, $filename) {
        $pathname = mb_substr($filename, mb_strlen(osm_path($root)) + 1);

        $exists = $this->files->query([
            "root = '{$root}'",
            "pathname = '{$pathname}'",
        ])->value('id');

        if ($exists) {
            return false;
        }

        $this->deleteFile(File::new(['filename_' => $filename]));

        return true;
    }

    protected function collectOrphanRecords() {
        if (!$this->full) {
            return;
        }

        foreach ($this->files->each() as $file) {
            if (!is_file($file->filename_)) {
                $this->deleteFile($file);
            }
        }
    }

    protected function delete($where) {
        $this->transaction(function() use ($where) {
            foreach ($this->files->each($where) as $file) {
                $this->deleteFile($file);
            }
        });
    }

    protected function deleteFile(File $file) {
        if (!$this->dry_run) {
            $this->files->deleteFile($file);
        }

        if ($this->callback) {
            call_user_func($this->callback, $file);
        }
    }

    protected function transaction(callable $callback) {
        if ($this->dry_run) {
            $callback();
        }

        $this->db->connection->transaction($callback);
    }

}