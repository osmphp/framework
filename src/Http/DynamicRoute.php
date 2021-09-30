<?php

namespace Osm\Framework\Http;

use FastRoute\Dispatcher;
use Osm\Core\Exceptions\NotImplemented;

/**
 * @property Dispatcher $dispatcher
 */
class DynamicRoute extends Route
{
    public function match(): ?Route {
        $dispatched = $this->dispatcher->dispatch(
            $this->http->request->getMethod(), $this->http->path);

        if ($dispatched[0] !== Dispatcher::FOUND) {
            return null;
        }

        if (is_array($dispatched[1])) {
            foreach ($dispatched[1] as $className => $data) {
                $new = "{$className}::new";
                return $new(array_merge($data, $dispatched[2]));
            }
        }

        $new = "{$dispatched[1]}::new";
        return $new($dispatched[2]);
    }

    protected function get_dispatcher(): Dispatcher {
        throw new NotImplemented($this);
    }

}