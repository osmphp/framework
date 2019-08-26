<?php

namespace Osm\Framework\Validation\Exceptions;

use Osm\Framework\Http\Exceptions\HttpError;

class ValidationFailed extends HttpError
{
    public $error = 'validation_failed';
    public $errors;

    public function __construct($message, $errors, \Throwable $previous = null) {
        parent::__construct($message, null, $previous);
        $this->errors = $errors;
    }
}