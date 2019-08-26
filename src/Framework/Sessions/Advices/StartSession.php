<?php

namespace Osm\Framework\Sessions\Advices;

use Carbon\Carbon;
use Osm\Core\App;
use Osm\Framework\Areas\Area;
use Osm\Framework\Http\Advice;
use Osm\Framework\Sessions\Session;
use Osm\Framework\Sessions\Stores\Store;
use Osm\Framework\Settings\Settings;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property Area $area @required
 * @property Request $symfony_request @required
 * @property Store|Session[] $sessions @required
 * @property Settings $settings @required
 * @property string $session_class
 * @property int $time_to_live @required
 * @property string $cookie_name @required
 * @property string $cookie_path @required
 * @property string $cookie_domain
 * @property bool $cookie_secure @required
 * @property bool $cookie_http_only @required
 * @property string $cookie_same_site
 */
class StartSession extends Advice
{
    protected function default($property) {
        global $m_app; /* @var App $m_app */

        switch ($property) {
            case 'area': return $m_app->area_;
            case 'symfony_request': return $m_app->request->symfony_request;
            case 'settings': return $m_app->settings;
            case 'time_to_live': return $this->settings->{"{$this->area->name}_session_time_to_live"};
            case 'cookie_name': return (env('APP_ENV') == 'testing' ? 'TESTING_' : '') .
                $this->settings->{"{$this->area->name}_session_cookie_name"};
            case 'cookie_path': return $this->settings->{"{$this->area->name}_session_cookie_path"};
            case 'cookie_domain': return $this->settings->{"{$this->area->name}_session_cookie_domain"};
            case 'cookie_secure': return $this->settings->{"{$this->area->name}_session_cookie_secure"};
            case 'cookie_http_only': return $this->settings->{"{$this->area->name}_session_cookie_http_only"};
            case 'cookie_same_site': return $this->settings->{"{$this->area->name}_session_cookie_same_site"};
            case 'sessions': return $m_app->session_stores['main'];
            case 'session_class': return $this->area->session_class;
        }
        return parent::default($property);
    }

    public function around(callable $next) {
        global $m_app; /* @var App $m_app */

        $m_app->session = $this->load();
        $this->gc();
        $response = $next();
        $this->save($response);
        return $response;
    }

    protected function load() {
        if (($id = $this->symfony_request->cookies->get($this->cookie_name)) && ($session = $this->sessions[$id])) {
            return $session;
        }

        return Session::new($this->session_class ? ['class' => $this->session_class] : []);
    }

    protected function save(Response $response) {
        global $m_app; /* @var App $m_app */

        $session = $m_app->session;

        $this->sessions[$session->id] = $session;
        $response->headers->setCookie(new Cookie($this->cookie_name, $session->id,
            Carbon::now()->addMinutes($this->time_to_live),
            $this->cookie_path, $this->cookie_domain, $this->cookie_secure, $this->cookie_http_only, false,
            $this->cookie_same_site));
    }

    protected function gc() {
        if (rand(0, 99) >= 2) {
            return;
        }

        $this->sessions->gc($this->time_to_live * 60);
    }
}