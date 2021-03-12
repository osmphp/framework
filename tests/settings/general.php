<?php

declare(strict_types=1);

/* @see \Osm\Framework\Settings\Hints\Settings */
return (object)[
    'locale' => 'lt_LT',

    /* @see \Osm\Framework\Logs\Hints\LogSettings */
    'logs' => (object)[
        'elastic' => true,
        'db' => true,
    ],
];