<?php

use Osm\Framework\Testing\DbTestSuite;

return [
    'unit' => [ 'title' => osm_t('Unit Tests'), 'sort_order' => 10],
    'db' => [ 'title' => osm_t('Database Tests'), 'sort_order' => 20, 'class' => DbTestSuite::class,
        'modules' => [],
    ],
    'app' => [ 'title' => osm_t('Application Tests'), 'sort_order' => 30],
    'docs' => [ 'title' => osm_t('Documentation Tests'), 'sort_order' => 40, 'optional' => true],
    'privileged' => [ 'title' => osm_t("Privileged App Tests"), 'sort_order' => 35, 'optional' => true],
];
