<?php

namespace Osm\Framework\Installer;

use Illuminate\Console\OutputStyle;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $yes @required
 * @property string $no @required
 * @property OutputStyle $output @temp
 * @property callable $required @required
 */
class Question extends Object_
{
    public function default($property) {
        switch ($property) {
            case 'yes': return (string)osm_t("Yes");
            case 'no': return (string)osm_t("No");
            case 'required': return function ($value) { return $this->required($value); };

        }
        return parent::default($property);
    }

    public function ask() {
        throw new NotImplemented();
    }

    protected function required($value) {
        if (trim($value) == '') {
            throw new \Exception(osm_t("The value cannot be empty"));
        }

        return $value;
    }
}