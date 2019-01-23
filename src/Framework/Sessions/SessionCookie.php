<?php

namespace Manadev\Framework\Sessions;

use Manadev\Core\Object_;

/**
 * @property string $name @required @part
 * @property string $path @required @part
 * @property string $domain @required @part
 * @property string
 */
class SessionCookie extends Object_
{

    public function getExpirationDate() {
    }
}