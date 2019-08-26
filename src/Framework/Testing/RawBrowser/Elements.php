<?php

namespace Osm\Framework\Testing\RawBrowser;

use Osm\Framework\Testing\Browser\Elements as BaseElements;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @property Document $parent
 * @property Crawler $crawler @required
 */
class Elements extends BaseElements
{
    /**
     * @return string
     */
    public function text() {
        return $this->crawler->text();
    }
}