<?php

namespace Osm\Data\Files;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\Files\Exceptions\PrefixCantBeInferred;
use Osm\Data\Files\Exceptions\SuffixCantBeInferred;

/**
 * Constructor arguments. For newly imported/uploaded file, pass `root`,
 * `requested_filename` and optionally `path`. For existing files, pass all the
 * columns of the file record in the `files` table, basically all of the
 * listed below, except `requested_filename`:
 *
 * @property string $root @required "public/files"
 * @property string $path "products"
 * @property string $requested_filename @required "jeans.jpg"
 * @property string $filename @required "ab/1e/jeans-2.jpg"
 * @property string $uid @required "ab1e...", omit for new files
 * @property string $prefix "ab/1e"
 * @property string $name @required "jeans"
 * @property string $suffix "-2"
 * @property string $ext "jpg"
 *
 * Computed properties:
 *
 * @property string $root_ @required
 * @property string $path_ @required
 * @property string $filename_ @required
 * @property array $insert_data @required
 */
class File extends Object_
{
    const MAX_FILES_PER_DIRECTORY = 256;
    const MAX_FILENAME_COLLISIONS = 256;

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'uid': return sha1(uniqid("", true));
            case 'root_': return $this->isRelativePath($this->root)
                ? $osm_app->path($this->root)
                : $this->root;
            case 'path_': return $this->path
                ? "{$this->root_}/{$this->path}"
                : $this->root_;
            case 'prefix': return $this->getPrefix();
            case 'name': return pathinfo($this->requested_filename,
                PATHINFO_FILENAME);
            case 'ext': return pathinfo($this->requested_filename,
                PATHINFO_EXTENSION);
            case 'suffix':
                return is_file($this->getAbsoluteFilename(null))
                ? $this->getSuffix([$this, is_numeric($this->name)
                    ? 'generateAlphabeticSuffix'
                    : 'generateNumericSuffix'])
                : null;
            case 'filename': return $this->getRelativeFilename($this->suffix);
            case 'filename_': return $this->getAbsoluteFilename($this->suffix);
        }

        return parent::default($property);
    }

    protected function isRelativePath($root) {
        return !preg_match('/^\\/|\\\\|\w:\\\\/', $root);
    }

    protected function getPrefix() {
        for ($path = $this->path_, $result = '', $i = 0; $i < 20; $i++) {
            if ($this->countFiles($path) < static::MAX_FILES_PER_DIRECTORY) {
                return $result ?: null;
            }

            $dir = mb_substr($this->uid, $i * 2, 2);
            $path .= "/{$dir}";
            if ($result) {
                $result .= '/';
            }
            $result .= $dir;
        }

        throw new PrefixCantBeInferred(osm_t(
            "Prefix for the file ':file' can be inferred.",
            ['file' => $this->filename]));
    }

    protected function getSuffix($callback) {
        for ($i = 0; $i < static::MAX_FILENAME_COLLISIONS; $i++) {
            $result = '-' . $callback($i);
            if (!is_file($this->getAbsoluteFilename($result))) {
                return $result;
            }
        }

        throw new SuffixCantBeInferred(osm_t(
            "Suffix for the file ':file' can be inferred.",
            ['file' => $this->filename]));
    }

    protected function countFiles($path) {
        $result = 0;
        if (!is_dir($path)) {
            return $result;
        }

        foreach (new \DirectoryIterator($path) as $fileInfo)
        {
            if ($fileInfo->isDot() || $fileInfo->isDir()) {
                continue;
            }

            if (mb_strpos($fileInfo->getFilename(), '.') === 0) {
                continue;
            }

            $result++;
        }

        return $result;
    }

    protected function getRelativeFilename($suffix) {
        return ($this->prefix ? $this->prefix . '/' : '')
            . "{$this->name}{$suffix}"
            . ($this->ext ? '.' . $this->ext : '');
    }

    protected function getAbsoluteFilename($suffix) {
        return "{$this->path_}/{$this->getRelativeFilename($suffix)}";
    }

    protected function generateNumericSuffix($i) {
        return $i + 2;
    }

    protected function generateAlphabeticSuffix($i) {
        static $resolutionSymbols = 'abcdefghijklmnopqrstuvwxyz';
        static $numericSymbols = '0123456789abcdefghijklmnop';

        $number26base = base_convert(strval($i), 10, 26);
        return strtr($number26base, $numericSymbols, $resolutionSymbols);
    }
}