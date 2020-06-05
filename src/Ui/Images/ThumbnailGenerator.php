<?php

namespace Osm\Ui\Images;

use Osm\Core\App;
use Osm\Core\Exceptions\NotSupported;
use Osm\Core\Object_;
use Osm\Data\Files\File;
use Osm\Data\Files\Files;
use Osm\Ui\Images\Hints\SizeHint;

/**
 * Constructor arguments:
 *
 * @property  File $file @required
 * @property  object|SizeHint $size @required
 *
 * Dependencies:
 *
 * @property Files $files @required
 *
 * Computed properties:
 *
 * @property File $thumbnail @required
 * @property resource $source @required
 * @property bool $transparent @required
 * @property bool $transparent_ @required
 * @property bool $true_color @required
 * @property resource $target @required
 */
class ThumbnailGenerator extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];

            case 'thumbnail': return $this->getThumbnail();
            case 'source': return $this->getSource();
            case 'transparent':
                return imagecolortransparent($this->source) != -1;
            case 'transparent_': return $this->file->image_type == IMAGETYPE_PNG
                ? ((ord(file_get_contents($this->file->filename_,
                    false, null, 25, 1)
                    ) & 6 & 4) == 4)
                : $this->transparent;
            case 'true_color': return $this->getTrueColor();
            case 'target': return $this->true_color
                ? imagecreatetruecolor($this->size->width, $this->size->height)
                : imagecreate($this->size->width, $this->size->height);
        }

        return parent::default($property);
    }

    /**
     * @param File $file
     * @param object|SizeHint $size
     *
     * @return File
     */
    public static function generate(File $file, $size) {
        return static::new([
            'file' => $file,
            'size' => $size,
        ])->doGenerate();
    }

    protected function getThumbnail() {
        $filename = $this->file->name .
            "-{$this->size->width}x{$this->size->height}";

        if ($this->file->ext) {
            $filename .= ".{$this->file->ext}";
        }

        $data = [
            'root' => $this->file->root,
            'path' => $this->file->path,
            'requested_filename' => $filename,

            'width' => $this->size->width,
            'height' => $this->size->height,
        ];

        foreach ($this->files->reference_columns as $column) {
            $data[$column] = $this->file->$column;
        }

        $data['original_file'] = $this->file->id;

        return File::new($data);
    }

    protected function createFile(File $file) {
    }

    protected function getSource() {
        $filename = $this->file->filename_;

        switch ($this->file->image_type) {
            case IMAGETYPE_GIF: return imagecreatefromgif($filename);
            case IMAGETYPE_JPEG: return imagecreatefromjpeg($filename);
            case IMAGETYPE_PNG: return imagecreatefrompng($filename);
            default:
                throw new NotSupported(sprintf(
                    "%s file format is not supported.", $filename));
        }
    }

    protected function getTrueColor() {
        switch ($this->file->image_type) {
            case IMAGETYPE_PNG: return $this->transparent;
            case IMAGETYPE_JPEG: return true;
            default: return false;
        }
    }

    protected function doGenerate() {
        try {
            $this->fillBackground();

            imagecopyresampled($this->target, $this->source,
                0, 0, 0, 0,
                $this->size->width, $this->size->height,
                $this->file->width, $this->file->height);

            imageinterlace($this->target, true);

            $this->save();

            $this->files->insert($this->thumbnail);

            return $this->thumbnail;
        }
        finally {
            imagedestroy($this->source);
            imagedestroy($this->target);
        }
    }

    protected function fillBackground() {
        if ($this->transparent) {
            if ($this->fillTransparentBackground()) {
                return true;
            }
        }

        $color = imagecolorallocate($this->target, 255, 255, 255);
        return imagefill($this->target, 0, 0, $color);
    }

    protected function fillTransparentBackground() {
        if (!imagealphablending($this->target, false)) {
            return false;
        }
        if (($color = imagecolorallocatealpha($this->target,
            0, 0, 0, 127)) === false)
        {
            return false;
        }
        if (!imagefill($this->target, 0, 0, $color)) {
            return false;
        }
        if (!imagesavealpha($this->target, true)) {
            return false;
        }

        return true;
    }

    protected function save() {
        $filename = osm_make_dir_for($this->thumbnail->filename_);

        switch ($this->file->image_type) {
            case IMAGETYPE_GIF: imagegif($this->target, $filename); break;
            case IMAGETYPE_JPEG: imagejpeg($this->target, $filename); break;
            case IMAGETYPE_PNG: imagepng($this->target, $filename, 9); break;
            default:
                throw new NotSupported(sprintf(
                    "%s file format is not supported.", $filename));
        }
    }
}