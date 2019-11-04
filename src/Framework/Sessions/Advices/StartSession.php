<?php

namespace Osm\Framework\Sessions\Advices;

use Carbon\Carbon;
use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Areas\Areas;
use Osm\Framework\Http\Advice;
use Osm\Framework\Sessions\Session;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Areas|Area[] $areas @required
 * @property string $area @required
 * @property Request $symfony_request @required
 */
class StartSession extends Advice
{
    protected function default($property) {
        global $osm_app; /* @var App $osm_app */

        switch ($property) {
            case 'areas': return $osm_app->areas;
            case 'area': return $osm_app->area;
            case 'symfony_request': return $osm_app->request->symfony_request;
        }
        return parent::default($property);
    }

    public function around(callable $next) {
        $gc = rand(0, 99) < 2;
        foreach ($this->areas as $area) {
            if (!$area->sessions) {
                continue;
            }

            $this->load($area);
            if ($gc) {
                $area->sessions->gc();
            }
        }

        $response = $next();

        foreach ($this->areas as $area) {
            if (!$area->sessions) {
                continue;
            }

            if (!$area->session) {
                continue;
            }

            $this->save($response, $area);
        }

        return $response;
    }

    /**
     * @param Area $area
     * @return Session
     */
    protected function load($area) {
        $cookie = $area->sessions->cookie_name;

        if (!($id = $this->symfony_request->cookies->get($cookie))) {
            return $area->session = $this->start($area);
        }

        if (!($session = $area->sessions[$id])) {
            return $area->session = $this->start($area);
        }

        return $area->session = $session;
    }

    /**
     * @param Area $area
     * @return Session
     */
    protected function start($area) {
        if ($area->name != $this->area) {
            return null;
        }

        return Session::new(['class' => $area->sessions->session_class]);
    }

    /**
     * @param Response $response
     * @param Area $area
     */
    protected function save(Response $response, $area) {
        $area->sessions[$area->session->id] = $area->session;
        $response->headers->setCookie(new Cookie(
            $area->sessions->cookie_name,
            $area->session->id,
            Carbon::now()->addMinutes($area->sessions->time_to_live),
            $area->sessions->cookie_path,
            $area->sessions->cookie_domain,
            $area->sessions->cookie_secure,
            $area->sessions->cookie_http_only,
            false,
            $area->sessions->cookie_same_site
        ));
    }
}