<?php

namespace Osm\Data\Files;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Files\Exceptions\CantUploadWithoutSession;
use Osm\Data\Files\Exceptions\InvalidContentLength;
use Osm\Data\Files\Hints\FileHint;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Areas\Area;
use Osm\Framework\Db\Db;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Url;
use Osm\Framework\Sessions\Session;
use Osm\Framework\Sessions\Stores\Store;
use Osm\Framework\Validation\Exceptions\ValidationFailed;

/**
 * @property Request $request @required
 * @property Db|TableQuery[] $db @required
 * @property Url $url @required
 * @property Area $area @required
 */
class Files extends Object_
{
    const PUBLIC = 'public/files';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'request': return $osm_app->request;
            case 'db': return $osm_app->db;
            case 'url': return $osm_app->url;
            case 'area': return $osm_app->area_;
        }

        return parent::default($property);
    }

    public function validateImage() {
        static $types = ['image/jpeg', 'image/gif', 'image/png', 'image/svg'];

        if (!in_array($this->request->content_type, $types)) {
            throw new ValidationFailed(osm_t("':file' is not an image", [
                'file' => $this->request->content_name,
            ]));
        }

        return $this;
    }

    public function validateNotHidden() {
        if (mb_strpos($this->request->content_name, '.') === 0) {
            throw new ValidationFailed(osm_t("Can't upload hidden file ':file'", [
                'file' => $this->request->content_name,
            ]));
        }

        return $this;
    }

    /**
     * @param $root
     * @param array $options
     * @return object|FileHint
     */
    public function upload($root, $options = []) {
        $file = File::new(array_merge([
            'root' => $root,
            'requested_filename' => $this->request->content_name,
        ], $options));

        file_put_contents(osm_make_dir_for($file->filename_),
            $this->request->content);

        if (filesize($file->filename_) !== $this->request->content_length) {
            throw new InvalidContentLength(osm_t(
                "The size of the uploaded file doesn't match the file size estimated by the browser."));
        }

        $this->insert($file);

        $result = [
            'uid' => $file->uid,
            'filename' => $file->filename,
        ];

        if ($root === static::PUBLIC) {
            $result['url'] = $file->url;
        }

        return (object)$result;
    }

    protected function insert(File $file) {
        $data = [];
        $hasReference = false;

        foreach ($this->db->tables['files']->columns as $column) {
            if ($column->name === 'id') {
                continue;
            }

            if (($value = $file->{$column->name}) === null) {
                continue;
            }

            $data[$column->name] = $value;

            if ($column->pinned) {
                $hasReference = true;
            }
        }

        if (!$hasReference) {
            if (!$this->area->session) {
                throw new CantUploadWithoutSession(osm_t(
                    "You can upload a file in an area without a session cookie."));
            }
            $data['session'] = $this->area->session->id;
        }

        return $this->db['files']->insert($data);
    }

    public function deleteExpiredSessionFiles(Area $area) {
        if (!($sessions = $area->sessions)) {
            throw new NotSupported("Deleting expired session files " .
                "is only possible while handling a HTTP request in an area " .
                "having a session cookie");
        }

        $ids = $this->db['files']->distinct()->values("session");

        foreach ($ids as $id) {
            if (!$sessions[$id]) {
                $this->delete("session = '{$id}'");
            }
        }
    }

    public function delete($where) {
        $files = $this->db['files']->where($where)->get($this->columns());
        foreach ($files as $file) {
            $file_ = File::new((array)$file);

            if (is_file($file_->filename_)) {
                unlink($file_->filename_);
            }

            $this->db['files']->where("id = {$file->id}")->delete();
        }
    }

    protected function columns() {
        return ['id', 'uid', 'root', 'path', 'prefix', 'name', 'suffix', 'ext',
            'filename'];
    }


}