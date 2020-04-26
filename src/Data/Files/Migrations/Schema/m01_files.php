<?php

namespace Osm\Data\Files\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_files extends Migration
{
    public function up() {
        $this->db->create('files', function (Blueprint $table) {
            $table->string('session')->title("Session");

            $table->string('name')->title("Name")
                ->required();
            $table->string('ext', 10)->title("Extension")
                ->required();
            $table->string('scope')->title("Scope");
            $table->string('title')->title("Title");
            $table->int('sort_order')->title("Sort Order");
        });

        $this->db->create('files_on_disk', function (Blueprint $table) {
            $table->int('file')->title("File")
                ->unsigned()->required();
                // ->references('files.id'); intentionally no ref. integrity

            $table->string('filename')->title("Filename")
                ->required();
        });

        $this->db->alter('urls', function (Blueprint $table) {
            $table->int('file')->title("File")
                ->unsigned()
                ->references('files.id')->on_delete('cascade');
        });
    }

    public function down() {
        $this->db->alter('urls', function (Blueprint $table) {
            $table->dropColumns('file');
        });

        $this->db->drop('files_on_disk');
        $this->db->drop('files');
    }
}
