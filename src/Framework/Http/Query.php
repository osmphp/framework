<?php

namespace Osm\Framework\Http;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Core\Profiler;
use Osm\Data\Sheets\Query as SheetQuery;

/**
 * @property array $__items @required
 *
 * @see \Osm\Ui\DataTables\Module:
 *      @property SheetQuery $data_table @required Typical name of data table query parameter which returns
 *          fully prepared sheet query
 */
class Query extends Object_
{
    public function __get($property) {
        global $m_app; /* @var App $app */
        global $m_profiler; /* @var Profiler $m_profiler */

        switch ($property) {
            case '__items':
                if ($m_profiler) $m_profiler->start(__METHOD__, 'urls');
                try {
                    if ($m_app->controller) {
                        return $this->__items = $m_app->controller->query;
                    }

                    return $m_app->area ? $m_app->area_->query : [];
                }
                finally {
                    if ($m_profiler) $m_profiler->stop(__METHOD__);
                }
        }

        if (($value = parent::__get($property)) !== null) {
            return $value;
        }

        return $this->offsetGet($property);
    }

    public function offsetGet($offset) {
        return $this->__items[$offset];
    }

    public function offsetExists($offset) {
        return isset($this->__items[$offset]);
    }

    public function all() {
        return $this->__items;
    }
}