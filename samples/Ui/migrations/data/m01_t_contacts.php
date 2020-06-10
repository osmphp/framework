<?php

namespace Osm\Samples\Ui\Migrations\Data;

use Faker\Factory as Faker;
use Osm\Core\App;
use Osm\Data\Files\Files;
use Osm\Framework\Migrations\Migration;
use Osm\Samples\Ui\Module;
use Osm\Samples\Ui\OptionLists\ContactGroups;

/**
 * Dependencies:
 *
 * @property ContactGroups $groups @required
 * @property Files $files @required
 * @property Module $module @required
 *
 * Computed properties:
 *
 * @property string $path @required
 * @property string[] $images @required
 */
class m01_t_contacts extends Migration
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'groups': return $osm_app->option_lists['t_contact_groups'];
            case 'files': return $osm_app[Files::class];
            case 'module': return $osm_app->modules['Osm_Samples_Ui'];

            case 'path': return osm_make_dir($osm_app->path(
                "{$this->module->path}/files/contact-images"));
            case 'images': return glob("{$this->path}/*.jpg");
        }
        return parent::default($property);
    }

    public function up() {
        $faker = Faker::create();

        for ($i = 0; $i < 500; $i++) {
            $contact = $this->db['t_contacts']->insert([
                'name' => "{$faker->firstName} {$faker->lastName}",
                'phone' => $faker->phoneNumber,
                'email' => $faker->email,
                'group' => $faker->randomElement(array_merge([null],
                    $this->groups->items->keys()->toArray())),
                'salary' => $faker->randomDigit !== 0
                    ? $faker->randomFloat(2, 300.0, 3000.0)
                    : null,
            ]);

            $this->db['t_contacts']->where("id = {$contact}")->update([
                'image' => $this->random($contact, $faker->randomElement(
                    array_merge([null], $this->images))),
            ]);
        }
    }

    protected function random($contact, $filename) {
        if (!$filename) {
            return null;
        }

        return $this->files->import(Files::PUBLIC, $filename, [
            'path' => 't_contacts',
            't_contact' => $contact,
        ]);
    }
}