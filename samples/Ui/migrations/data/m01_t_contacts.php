<?php

namespace Osm\Samples\Ui\Migrations\Data;

use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Migrations\Migration;
use Osm\Samples\Ui\OptionLists\ContactGroups;

/**
 * @property ContactGroups $groups @required
 * @property Files $files @required
 */
class m01_t_contacts extends Migration
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'groups': return $osm_app->option_lists['t_contact_groups'];
            case 'files': return $osm_app[Files::class];
        }
        return parent::default($property);
    }

    public function up() {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $contact = $this->db['t_contacts']->insert([
                'name' => "{$faker->firstName} {$faker->lastName}",
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'group' => $faker->randomElement(array_merge([null],
                    $this->groups->items->keys()->toArray())),
                'salary' => $faker->randomDigit !== 0
                    ? $faker->randomFloat(2, 300.0, 10000.0)
                    : null,
            ]);

            $this->db['t_contacts']->where("id = {$contact}")->update([
                'image' => $this->import($contact, $faker->image()),
            ]);
        }
    }

    protected function import($contact, $filename) {
        try {
            return $this->files->import(Files::PUBLIC, $filename, [
                'path' => 't_contacts',
                't_contact' => $contact,
            ]);
        } finally {
            unlink($filename);
        }
    }
}