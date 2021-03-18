<?php

declare(strict_types=1);

namespace Osm\Framework\Browser;

use Osm\Core\Object_;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property string $base_url
 * @property AbstractBrowser $symfony_browser
 */
class Browser extends Object_
{
    public function get(string $url): Crawler {
        return $this->symfony_browser->request('GET', $url);
    }

    protected function get_symfony_browser(): AbstractBrowser {
        return new Client();
    }
}