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

        $new = "{$dispatched[1]}::new";

        return $new($dispatched[2]);
    }

    protected function get_dispatcher(): Dispatcher {
        throw new NotImplemented($this);
    }

}