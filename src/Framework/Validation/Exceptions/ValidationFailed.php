<?php

namespace Osm\Framework\Validation\Exceptions;

use Osm\Framework\Http\Exceptions\HttpError;

class ValidationFailed extends HttpError
{
    public $error = 'validation_failed';
    public $errors;

    public function __construct($errors = [], \Throwable $previous = null) {
        if (is_array($errors)) {
            $message = osm_t("Validation failed");
        }
        else {
            $message = (string)$errors;
            $errors = [];
        }

        parent::__construct($message, null, $previous);
        $this->errors = $errors;
    }
}