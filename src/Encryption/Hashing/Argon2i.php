<?php

namespace Osm\Framework\Encryption\Hashing;

use Osm\Core\App;
use Osm\Framework\Settings\Settings;

/**
 * @property Settings $settings @required
 */
class Argon2i extends Hashing
{
    public $algorithm = 2; // PASSWORD_ARGON2I;

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'settings': return $osm_app->settings;
            case 'options': return [
                'memory_cost' => $this->settings->hashing_argon2_memory_cost,
                'time_cost' => $this->settings->hashing_argon2_time_cost,
                'threads' => $this->settings->hashing_argon2_threads,
            ];
        }
        return parent::default($property);
    }
}