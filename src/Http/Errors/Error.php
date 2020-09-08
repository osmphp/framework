<?php

namespace Osm\Framework\Http\Errors;

use Osm\Core\App;
use Osm\Core\Object_;
use Osm\Framework\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property string $name @required @part
 * @property int $status @required
 * @property string $status_text @required
 * @property string $content_type @required
 * @property string $content
 * @property Request $request @required
 * @property Response $response
 * @property \Throwable $e @temp
 */
class Error extends Object_
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'request': return $osm_app->request;
            case 'status_text': return $this->getStatusText();
            case 'content_type': return $this->request->ajax ? 'application/json' : 'text/plain';
            case 'content': return $this->request->ajax
                ? json_encode(['error' => $this->name], JSON_PRETTY_PRINT)
                : $this->e->getMessage();
        }
        return parent::default($property);
    }

    protected function getStatusText() {
        $result = $this->e->getMessage();

        if (($pos = mb_strpos($result, "\n")) !== false) {
            $result = mb_substr($result, 0, $pos);
        }

        return $result;
    }

}