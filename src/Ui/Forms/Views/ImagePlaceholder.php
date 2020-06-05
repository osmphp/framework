<?php

namespace Osm\Ui\Forms\Views;

use Osm\Framework\Views\View;
use Osm\Ui\Images\Views\Image;

/**
 * @property Image $image @required @part
 */
class ImagePlaceholder extends View
{
    public $template = 'Osm_Ui_Forms.image-placeholder';
}