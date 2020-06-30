<?php

namespace Osm\Samples\Ui\Migrations\Schema;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_t_contacts extends Migration
{
    public function up() {
        $this->db->create('t_contacts', function (Blueprint $table) {
            $table->string('name')->title("Name")->required()->index();
            $table->int('image')->title("Image")
                ->unsigned()->references('files.id');
            $table->string('group', 20)->title("Group")->index();
            $table->decimal('salary')->title("Salary")->index();
            $table->string('phone')->title("Phone")->required()->index();
            $table->string('email')->title("Email")->required()->index();
        });

        $this->db->alter('files', function(Blueprint $table) {
            $table->int('t_contact')->title("Testing Contact")
                ->unsigned()->pinned()
                ->references('t_contacts.id')->on_delete('set null');
        });
    }

    public function down() {
        $this->db->alter('files', function(Blueprint $table) {
            $table->dropColumns('t_contact');
        });

        $this->db->drop('t_contacts');
    }
}