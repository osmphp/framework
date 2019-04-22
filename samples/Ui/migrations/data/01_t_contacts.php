<?php

namespace Manadev\Samples\Ui\Migrations\Data;

use Manadev\Data\Tables\Blueprint;
use Manadev\Framework\Migrations\Migration;

class TContacts extends Migration
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