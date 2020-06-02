<?php

namespace Osm\Ui\Forms\Controllers;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Http\Controller;

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
        return (object)$this->files->validateImage()->upload(Files::PUBLIC);
    }
}