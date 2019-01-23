<?php

namespace Manadev\Framework\Http;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Http\Exceptions\InvalidParameter;

/**
 * @property string $name @required @part
 * @property bool $required @part
 * @property bool $transient @part
 * @property Request $request
 */
class Parameter extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'request': return $m_app->request;
        }
        return parent::default($property);
    }

    /**
     * @param array $query
     * @return mixed
     */
    public function parse($query) {
        if (!isset($query[$this->name])) {
            if ($this->required) {
                throw new InvalidParameter(m_("Missing required parameter ':name'", ['name' => $this->name]));
            }

            return null;
        }

        return $query[$this->name];
    }

    /**
     * @param array $query
     * @param mixed $value
     */
    public function generate(&$query, $value) {
        $query[$this->name] = $value;
    }
}