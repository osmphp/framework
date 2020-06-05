<?php

namespace Osm\Ui\Images;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\Files\File;
use Osm\Data\Files\Files;
use Osm\Data\TableQueries\TableQuery;
use Osm\Framework\Db\Db;
use Osm\Framework\Settings\Settings;
use Osm\Ui\Images\Hints\SizeHint;

/**
 * Dependencies:
 *
 * @property Settings $settings @required
 * @property Db|TableQuery[] $db @required
 * @property Files $files @required
 *
 * Computed properties:
 *
 * @property string[] $retina_densities @required
 */
class Thumbnails extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;
            case 'db': return $osm_app->db;
            case 'files': return $osm_app[Files::class];

            case 'retina_densities': return $this->getRetinaDensities();
        }

        return parent::default($property);
    }

    protected function getRetinaDensities() {
        $result = [];

        $densities = explode(' ',
            $this->settings->thumbnail_retina_densities ?? '');

        foreach ($densities as $density) {
            if (preg_match('/^(?<density>\\d+)x$/', $density,
                $match))
            {
                $result[$density] = (int)$match['density'];
            }
        }

        return $result;
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return object[]|SizeHint[]
     */
    public function retinaSizes($width, $height) {
        return array_map(function($density) use ($width, $height) {
            return (object)[
                'width' => $width * $density,
                'height' => $height * $density,
            ];
        }, $this->retina_densities);
    }

    /**
     * @param int $width
     * @param int $height
     *
     * @return object[]|SizeHint[]
     */
    public function sizes($width, $height) {
        return array_merge(
            ['' => (object)compact('width', 'height')],
        $this->retinaSizes($width, $height));
    }

    /**
     * @param int[] $ids
     * @param object[]|SizeHint[] $sizes
     *
     * @return File[]
     */
    public function get($ids, $sizes) {
        return $this->db['files']
            ->where("original_file IN (" . implode(', ', $ids) . ")")
            ->where(implode(' OR ', array_map(function($size) {
                /* @var SizeHint $size */
                return "width = {$size->width} AND height = {$size->height}";
            }, $sizes)))
            ->select(['id', 'original_file'])
            ->get($this->files->data_columns)
            ->map(function($data) { return File::new((array)$data); })
            ->keyBy([$this, 'key'])
            ->toArray();
    }

    /**
     * @param File $file
     * @param object[]|SizeHint[] $sizes
     * @param File[] $thumbnails
     *
     * @return File[]
     */
    public function generate(File $file, $sizes, $thumbnails) {
        if (strtolower($file->ext) == 'svg') {
            return [];
        }

        foreach ($sizes as $size) {
            $key = $this->key($size);

            if (isset($thumbnails[$key])) {
                continue;
            }

            if ($file->width < $size->width) {
                continue;
            }

            if ($file->height < $size->height) {
                continue;
            }

            $thumbnails[$key] = ThumbnailGenerator::generate($file, $size);
        }

        return $thumbnails;
    }

    /**
     * @param object|SizeHint $size
     * @return string
     */
    public function key($size) {
        return "{$size->width}x{$size->height}";
    }
}