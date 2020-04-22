<?php

namespace Osm\Samples\Ui\Migrations\Data;

use Osm\Data\Tables\Blueprint;
use Osm\Framework\Migrations\Migration;

class m01_t_contacts extends Migration
{
    public function up() {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 500; $i++) {
            $this->db['t_contacts']->insert([
                'full_name' => "{$faker->firstName} {$faker->lastName}",
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
            ]);
        }
    }
}