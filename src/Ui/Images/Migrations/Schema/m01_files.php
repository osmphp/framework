<?php

namespace Osm\Ui\Images\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_files extends Migration
{
    public function up() {
        $this->db->alter('files', function(Blueprint $table) {
            $table->int('width')->title("Width")
                ->unsigned();
            $table->int('height')->title("Height")
                ->unsigned();
            $table->int('original_file')->title("Original File")
                ->unsigned()->pinned()
                ->references('files.id')->on_delete('set null');
        });
    }

    public function down() {
        $this->db->alter('files', function(Blueprint $table) {
            $table->dropColumns(
                'width',
                'height',
                'original_file'
            );
        });
    }
}
