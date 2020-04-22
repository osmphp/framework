<?php

namespace Osm\Samples\Ui\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_t_contacts extends Migration
{
    public function up() {
        $this->db->create('t_contacts', function (Blueprint $table) {
            $table->string('full_name')->title("Full Name")->required()->index();
            $table->string('phone')->title("Phone")->required()->index();
            $table->string('email')->title("Email")->required()->index();
        });
    }

    public function down() {
        $this->db->drop('t_contacts');
    }
}