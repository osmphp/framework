<?php

namespace Osm\Ui\Images\Traits;

use Osm\Data\Files\File;

trait FileTrait
{
    protected function around_default(callable $proceed, $property) {
        $file = $this; /* @var File $file */

        switch ($property) {
            case 'image_size':
                return in_array(strtolower($file->ext), ['gif', 'jpg', 'png'])
                    ? getimagesize($file->filename_)
                    : null;
            case 'width': return $file->image_size[0] ?? null;
            case 'height': return $file->image_size[1] ?? null;
            case 'image_type': return $file->image_size[2] ?? null;
        }

        return $proceed($property);
    }
}