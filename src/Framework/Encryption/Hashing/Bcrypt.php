<?php

namespace Manadev\Framework\Encryption\Hashing;

use Manadev\Core\App;
use Manadev\Framework\Settings\Settings;

/**
 * @property Settings $settings @required
 */
class Bcrypt extends Hashing
{
    public $algorithm = PASSWORD_BCRYPT;

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'settings': return $m_app->settings;
            case 'options': return [
                'cost' => $this->settings->hashing_bcrypt_cost,
            ];
        }
        return parent::default($property);
    }
}