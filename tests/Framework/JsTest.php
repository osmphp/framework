<?php

namespace Manadev\Tests\Framework;


use Manadev\Framework\Testing\Browser\Browser;
use Manadev\Framework\Testing\Tests\AppTestCase;

class JsTest extends AppTestCase
{
    public function testJs() {
        $this->browse('chrome', function(Browser $browser) {
            $html = $browser->html('GET /tests/api/ajax');
        });
    }
}