<?php

namespace Osm\Framework\Http\Errors;

use Symfony\Component\HttpFoundation\Response;

class General extends Error
{
    public function default($property) {
        switch ($property) {
            case 'status': return Response::HTTP_INTERNAL_SERVER_ERROR;
            case 'content_type': return 'text/plain';
            case 'content': return env('APP_ENV') != 'production' ? $this->stackTrace() : '';
        }
        return parent::default($property);
    }

    protected function stackTrace() {
        $result = '';
        for ($e = $this->e; $e; $e = $e->getPrevious()) {
            if ($result) {
                $result = osm_t("\n\nThis exception was wrapped into another exception:\n\n") . $result;
            }

            $result = get_class($e) . ": " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . $result;
        }

        return $result;
    }
}