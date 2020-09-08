<?php

namespace Osm\Framework\Installer;

use Illuminate\Console\OutputStyle;
use Osm\Core\Exceptions\NotImplemented;
use Osm\Core\Object_;

/**
 * @property string $yes @required
 * @property string $no @required
 * @property OutputStyle $output @temp
 */
class Requirement extends Object_
{
    public function default($property) {
        switch ($property) {
            case 'yes': return (string)osm_t("Yes");
            case 'no': return (string)osm_t("No");
        }
        return parent::default($property);
    }

    /**
     * @return bool
     */
    public function check() {
        throw new NotImplemented();
    }
}