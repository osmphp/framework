<?php

namespace Manadev\Framework\Http\Errors;

use Manadev\Framework\Data\CollectionRegistry;
use Manadev\Framework\Http\Exceptions\NotFound;

class Errors extends CollectionRegistry
{
    public $class_ = Error::class;
    public $config = 'http_errors';
    public $not_found_message = "HTTP error ':name' not found";

    public function notFound(NotFound $e) {
        return $e->getMessage();
    }

    public function general(\Throwable $e) {
        return $this->stackTrace($e);
    }

    protected function stackTrace(\Throwable $e) {
        $result = '';
        for (; $e; $e = $e->getPrevious()) {
            if ($result) {
                $result = "\n\nThis exception was wrapped into another exception:\n\n" . $result;
            }

            $result = get_class($e) . ": " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . $result;
        }

        return $result;
    }
}