<?php

namespace Manadev\Framework\Localization;

use Manadev\Framework\Data\CollectionRegistry;

/**
 * @property string $locale @required @part
 */
class Translations extends CollectionRegistry
{
    public function default($property) {
        switch ($property) {
            case 'config': return "translations/{$this->locale}";
        }
        return parent::default($property);
    }

    protected function get() {
        $this->modified();
        return $this->config_;
    }
}