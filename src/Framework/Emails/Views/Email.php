<?php

namespace Osm\Framework\Emails\Views;

use Osm\Framework\Views\View;

/**
 * @property string $subject @required @part
 * @property string|array $from @required @part
 * @property string|array $to @required @part
 * @property View $body @required @part
 * @property string $body_ @required
 * @property string $plain @required
 */
class Email extends View
{
    protected function default($property) {
        switch ($property) {
            case 'body_': return (string)$this->body;
            case 'plain': return $this->getPlain();
        }

        return parent::default($property);
    }

    protected function getPlain() {
        $result = $this->body_;



        return strip_tags($result);
    }
}