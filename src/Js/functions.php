<?php

namespace Osm {
    function js(array $options): string {
        return str_replace('\'', '&apos;',
            str_replace('\\/', '/',
                json_encode((object)$options, JSON_PRETTY_PRINT)
            )
        );
    }
}