<?php

namespace Osm\Data\Sheets;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\OptionLists\OptionList;

/**
 * @property string $name @required @part
 * @property string $type @part If empty, the column doesn't have
 *      any additional processing
 *
 * @property Sheet $parent @required
 * @property string $formula @part
 *
 * OPTION column properties:
 *
 * @property string $option_list @required @part
 * @property OptionList $option_list_ @required
 */
class Column extends Object_
{
    const SECRET = 'secret';
    const OPTION = 'option';
    const FILE = 'file';

    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'option_list_': return $this->option_list ? $osm_app->option_lists[$this->option_list] : null;
        }
        return parent::default($property);
    }
}