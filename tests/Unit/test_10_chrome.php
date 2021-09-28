<?php

declare(strict_types=1);

namespace Osm\Framework\Tests\Unit;

use Facebook\WebDriver\WebDriverBy;
use Osm\Framework\Samples\App;
use Osm\Runtime\Apps;
use Symfony\Component\Panther\PantherTestCase;

class test_10_chrome extends PantherTestCase
{
    public function test_chrome() {
        $paths = Apps::paths(App::class);
        $client = static::createPantherClient([
            'webServerDir' => "{$paths->project}/public/{$paths->app_name}",
            'router' => "{$paths->project}/public/{$paths->app_name}/router.php",
        ]);

        $client->request('GET', '/test');
        $this->assertEquals('Hi', $client
            ->findElement(WebDriverBy::cssSelector('.test'))
            ->getText());
    }
}