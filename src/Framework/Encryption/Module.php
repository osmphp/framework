<?php

namespace Manadev\Framework\Encryption;

use Manadev\Core\App;
use Manadev\Core\Modules\BaseModule;
use Manadev\Core\Properties;
use Manadev\Framework\Encryption\Hashing\Hashing;
use Manadev\Framework\Encryption\Hashing\Hashings;
use Manadev\Framework\Settings\Settings;

/**
 * @property Hashings|Hashing[] $hashings @required
 */
class Module extends BaseModule
{
    public $traits = [
        Properties::class => Traits\PropertiesTrait::class,
    ];

    public $hard_dependencies = [
        'Manadev_Framework_Settings',
    ];

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'hashings': return $m_app->cache->remember('hashings', function($data) {
                return Hashings::new($data);
            });
        }
        return parent::default($property);
    }
}