<?php

namespace Osm\Data\Urls\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_urls extends Migration
{
    public function up() {
        $this->db->create('urls', function (Blueprint $table) {
            $table->string('scope', 20)->title("Scope")
                ->required();
            $table->string('url')->title("URL")
                ->required();
        });

        $this->db->create('urls__final', function (Blueprint $table) {
            $table->int('edit')->title("URL Edit")
                ->unsigned()->required()
                ->references('urls.id')->on_delete('cascade');
            $table->bool('redirect')->title("Redirect")
                ->required()->value(false);
            $table->string('suffix', 20)->title("Suffix")
                ->required();
            $table->string('url')->title("URL")
                ->required();
        });

    }

    public function down() {
        $this->db->drop('urls__final');
        $this->db->drop('urls');
    }
}
