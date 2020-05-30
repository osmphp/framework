<?php

namespace Osm\Data\Files;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\Files\Exceptions\CantUploadWithoutSession;
use Osm\Data\Files\Exceptions\InvalidContentLength;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Areas\Area;
use Osm\Framework\Db\Db;
use Osm\Framework\Http\Request;
use Osm\Framework\Http\Url;
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
    }

    public function validateNotHidden() {
        if (mb_strpos($this->request->content_name, '.') === 0) {
            throw new ValidationFailed(osm_t("Can't upload hidden file ':file'", [
                'file' => $this->request->content_name,
            ]));
        }
    }

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

        $id = $this->insert($file);

        $result = [
            'id' => $id,
            'uid' => $file->uid,
            'filename' => $file->filename,
        ];

        if ($root === static::PUBLIC) {
            $result['url'] = $this->url->toFile($file->filename);
        }

        return $result;
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

}