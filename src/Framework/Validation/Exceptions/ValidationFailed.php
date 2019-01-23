<?php

namespace Manadev\Framework\Validation\Exceptions;

use Manadev\Framework\Http\Exceptions\HttpError;

class ValidationFailed extends HttpError
{
    public $error = 'validation_failed';
    public $errors;

    public function __construct($message, $errors, \Throwable $previous = null) {
        parent::__construct($message, 0, $previous);
        $this->errors = $errors;
    }
}