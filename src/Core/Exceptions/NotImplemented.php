<?php

namespace Osm\Core\Exceptions;

class NotImplemented extends \Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        parent::__construct($message ?: 'Not implemented', $code, $previous);
    }
}