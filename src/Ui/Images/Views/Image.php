<?php

namespace Osm\Ui\Images\Views;

use Osm\Core\App;
use Osm\Data\Files\File;
use Osm\Data\Files\Files;
use Osm\Framework\Views\View;
use Osm\Ui\Images\Thumbnails;

/**
 * Dependencies:
 *
 * @property Files $files @required
 * @property Thumbnails $thumbnails_ @required
 *
 * Data properties:
 *
 * @property int|string $file
 * @property File $file_
 * @property File[] $thumbnails
 *
 * Style properties:
 *
 * @property int $width @required @part
 * @property int $height @required @part
 * @property string[] $attributes @required @part
 * @property bool $eager @part
 *
 * Computed properties:
 *
 * @property string[] $attributes_ @required
 * @property string $src @required
 * @property string $srcset
 */
class Image extends View
{
    public $template = 'Osm_Ui_Images.image';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
            case 'thumbnails_':
                return $osm_app[Thumbnails::class];

            case 'file_': return $this->file
                ? $this->files->first($this->file)
                : null;
            case 'thumbnails': return $this->getThumbnails();

            case 'attributes': return [];
            case 'attributes_': return $this->getAttributes();
            case 'src': return $this->getSrc();
            case 'srcset': return $this->getSrcset();

        }

        return parent::default($property);
    }

    protected function getAttributes() {
        // escape the values
        $attributes = array_map('e', $this->attributes);

        $attributes['width'] = $this->width;
        $attributes['height'] = $this->height;

        if (!$this->eager) {
            $attributes['loading'] = 'lazy';
        }

        $result = '';

        foreach ($attributes as $key => $value) {
            if ($result) {
                $result .= ' ';
            }

            $result .= "{$key}=\"{$value}\"";
        }

        return $result;
    }

    protected function getSrc() {
        $file = $this->thumbnails["{$this->width}x{$this->height}"]
            ?? $this->file_;

        return $file->url;
    }

    protected function getSrcset() {
        $result = '';

        $sizes = $this->thumbnails_->retinaSizes($this->width,
            $this->height);

        foreach ($sizes as $density => $size) {
            $thumbnail = $this->thumbnails["{$size->width}x{$size->height}"]
                ?? null;

            if (!$thumbnail) {
                continue;
            }

            if ($result) {
                $result .= ', ';
            }

            $result .= "{$thumbnail->url} {$density}";
        }

        return $result;
    }

    protected function getThumbnails() {
        $sizes = $this->thumbnails_->sizes($this->width, $this->height);
        $ids = $this->files->ids([$this->file_]);

        $thumbnails = $this->thumbnails_->get($ids, $sizes);
        return $this->thumbnails_->generate($this->file_, $sizes,
            $thumbnails);
    }
}