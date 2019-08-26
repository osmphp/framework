<?php

namespace Osm\Data\Sheets;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Data\OptionLists\OptionList;

/**
 * @property string $name @required @part
 * @property Sheet $parent @required
 *
 * @property string $formula @part If empty, name property is used as a formula
 * @property string $option_list @part If not empty, option title is added to search result
 * @property OptionList $option_list_
 */
class Column extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'option_list_': return $this->option_list ? $m_app->option_lists[$this->option_list] : null;
        }
        return parent::default($property);
    }
}