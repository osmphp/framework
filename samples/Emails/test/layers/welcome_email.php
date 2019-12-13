<?php

use Osm\Framework\Views\Views\Text;

return [
    '@include' => 'email',
    '#email' => [
        'from' => ['example@domain.com' => 'The Sender'],
        'subject' => osm_t("Welcome!"),
        'body' => Text::new(['contents' => 'Hello']),
    ],
];
