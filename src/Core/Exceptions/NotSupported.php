<?php

namespace Manadev\Core\Exceptions;

class NotSupported extends \Exception
{
    public function __construct($message = "", $code = 0, \Throwable $previous = null) {
        parent::__construct($message ?: 'Not supported', $code, $previous);
    }
}