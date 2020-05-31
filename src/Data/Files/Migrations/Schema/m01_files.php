<?php

namespace Osm\Data\Files\Migrations\Schema;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

/**
 * @property Files $files @required
 */
class m01_files extends Migration
{
    public function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'files': return $osm_app[Files::class];
        }
        return parent::default($property);
    }

    public function up() {
        $this->db->create('files', function (Blueprint $table) {
            $table->string('uid', 40)->title("UID")
                ->unique()->required();

            $table->string('area')->title("Area")
                ->index();
            $table->string('session')->title("Session")
                ->index();

            $table->string('root')->title("Root")
                ->required()->index();
            $table->string('path')->title("Path");
            $table->string('pathname')->title("Pathname")
                ->required()->index();
            $table->string('prefix')->title("Prefix");
            $table->string('name')->title("Name")
                ->required();
            $table->string('suffix')->title("Suffix");
            $table->string('ext')->title("Extension");
            $table->string('filename')->title("Filename")
                ->required();
        });
    }

    public function down() {
        $this->files->dropAllFiles();
        $this->db->drop('files');
    }
}
