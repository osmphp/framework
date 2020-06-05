<?php

namespace Osm\Ui\Forms\Controllers;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Http\Controller;
use Osm\Ui\Images\Views\Image;

/**
 * @property Files $files @required
 */
class Web extends Controller
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
        }

        return parent::default($property);
    }

    public function uploadImage() {
        $file = $this->files->validateImage()->upload(Files::PUBLIC);

        return (object)[
            'uid' => $file->uid,
            'filename' => $file->name,
            'html' => (string)Image::new([
                'id_' => null,
                'width' => 160,
                'height' => 160,
                'attributes' => [
                    'class' => 'form-section__image',
                ],
                'file_' => $file,
            ]),
        ];
    }
}