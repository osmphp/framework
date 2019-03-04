<?php

namespace Manadev\Framework\Testing\RawBrowser;

use Manadev\Framework\Testing\Browser\Elements as BaseElements;
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