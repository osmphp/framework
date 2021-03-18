<?php

declare(strict_types=1);

namespace Osm {

    use Osm\Framework\Browser;

    function browse(array $data = []): Browser\Browser {
        return Browser\Browser::new($data);
    }

    function browse_using_http(array $data = []): Browser\Browser {
        return Browser\HttpBrowser::new($data);
    }

    function browse_using_chrome(array $data = []): Browser\Browser {
        return Browser\ChromeBrowser::new($data);
    }
}