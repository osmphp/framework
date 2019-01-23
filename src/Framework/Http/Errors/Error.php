<?php

namespace Manadev\Framework\Http\Errors;

use Manadev\Core\App;
use Manadev\Core\Object_;
use Manadev\Framework\Http\Request;

/**
 * @property string $name @required @part
 * @property int $status @required
 * @property string $status_text @required
 * @property string $content_type @required
 * @property string $content
 * @property Request $request @required
 * @property \Throwable $e @temp
 */
class Error extends Object_
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'request': return $m_app->request;
        }
        return parent::default($property);
    }

    public function __get($property) {
        switch ($property) {
            case 'status_text': return $this->getStatusText();
            case 'content_type': return $this->request->ajax ? 'application/json' : 'text/plain';
            case 'content': return $this->request->ajax
                ? json_encode(['error' => $this->name], JSON_PRETTY_PRINT)
                : $this->e->getMessage();
        }
        return parent::__get($property);
    }

    protected function getStatusText() {
        $result = $this->e->getMessage();

        if (($pos = mb_strpos($result, "\n")) !== false) {
            $result = mb_substr($result, 0, $pos);
        }

        return $result;
    }

}