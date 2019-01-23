<?php

namespace Manadev\Framework\Data;

use Manadev\Core\App;
use Manadev\Framework\Cache\CacheItem;

/**
 * @property array $config @required @part
 * @property Advice[] $advices @required
 * @property int $count @required
 * @property Sorter $sorter @required
 * @property callable $callback @temp
 */
class AdviceRegistry extends CacheItem
{
    /**
     * @required @part
     * @var string
     */
    public $class_ = Advice::class;

    /**
     * @part
     * @var string
     */
    public $sort_by = 'sort_order';

    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'sorter': return $m_app[Sorter::class];
            case 'advices': return $this->createAdvices();
            case 'count': return count($this->advices);
        }
        return parent::default($property);
    }

    public function around(callable $callback) {
        if (!$this->count) {
            return $callback();
        }

        $currentCallback = $this->callback;
        $this->callback = $callback;
        try {
            return $this->advices[0]->around(function() {
                return $this->next(1);
            });
        }
        finally {
            $this->callback = $currentCallback;
        }
    }

    protected function next($advice) {
        if ($advice >= $this->count) {
            $callback = $this->callback;
            return $callback();
        }

        return $this->advices[$advice]->around(function() use ($advice) {
            return $this->next($advice + 1);
        });
    }

    protected function createAdvices() {
        global $m_app; /* @var App $m_app */

        $result = [];

        foreach ($this->config as $name => $data) {
            $result[$name] = $m_app->create($this->class_, $data, $name);
        }

        if ($this->sort_by) {
            $this->sorter->orderBy($result, $this->sort_by);
        }

        $result = array_values($result);

        return $result;
    }
}