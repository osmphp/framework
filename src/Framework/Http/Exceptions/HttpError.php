<?php

namespace Manadev\Framework\Http\Exceptions;

use Throwable;

class HttpError extends \Exception
{
    public $error = 'expected_error';

    public function __construct($message = "", $error = null, Throwable $previous = null) {
        parent::__construct($message, 0, $previous);

        if ($error) {
            $this->error = $error;
        }
    }
}