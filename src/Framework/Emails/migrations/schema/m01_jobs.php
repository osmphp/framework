<?php

namespace Osm\Framework\Emails\Migrations\Schema;

use Illuminate\Database\Schema\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_jobs extends Migration
{
    public function up() {
        $this->schema->table('jobs', function (Blueprint $table) {
            $table->longText('email')->nullable();
        });
    }
}