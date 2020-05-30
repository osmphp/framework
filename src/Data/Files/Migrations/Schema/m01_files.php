<?php

namespace Osm\Data\Files\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_files extends Migration
{
    public function up() {
        $this->db->create('files', function (Blueprint $table) {
            $table->string('uid', 40)->title("UID")
                ->unique()->required();

            $table->string('session')->title("Session")
                ->index();

            $table->string('root')->title("Root")
                ->required();
            $table->string('path')->title("Path");
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
        $this->db->drop('files');
    }
}
