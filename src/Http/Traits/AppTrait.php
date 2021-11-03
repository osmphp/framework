<?php

/** @noinspection PhpUnusedAliasInspection */
declare(strict_types=1);

namespace Osm\Framework\Http\Traits;

use Osm\Core\App;
use Osm\Core\Attributes\UseIn;
use Osm\Framework\Areas;
use Osm\Framework\Http\Hints\BaseUrl;
use Osm\Framework\Http\Http;
use Symfony\Component\HttpFoundation\Response;
use Osm\Framework\Cache\Attributes\Cached;
use function Osm\__;

/**
 * @property Http $http
 * @property BaseUrl[] $base_urls #[Cached('base_urls')]
 * @property ?string $base_url
 */
#[UseIn(App::class)]
trait AppTrait
{
    /** @noinspection PhpUnused */
    public function handleHttpRequest(array $data = []): Response {
        $this->http = Http::new($data);

        try {
            return $this->http->run();
        }
        finally {
            $this->http = null;
        }
    }

    /** @noinspection PhpUnused */
    protected function get_base_urls(): array {
        /* @var App $this */

        return [
            'api' => (object)[
                'base_url' => "{$this->settings->base_url}/api",
                'area_class_name' => Areas\Api::class,
            ],
            'admin' => (object)[
                'base_url' => "{$this->settings->base_url}/admin",
                'area_class_name' => Areas\Admin::class,
                'title' => __($this->settings->admin_title),
            ],
            'front' => (object)[
                'base_url' => $this->settings->base_url,
                'area_class_name' => Areas\Front::class,
                'title' => __($this->settings->title),
            ],
        ];
    }

    protected function get_base_url(): ?string {
        return $this->http->base_url;
    }
}