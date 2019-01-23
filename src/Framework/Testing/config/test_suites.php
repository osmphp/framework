<?php

use Manadev\Framework\Testing\DbTestSuite;

return [
    'unit_tests' => [ 'title' => m_('Unit Tests'), 'sort_order' => 10],
    'db_tests' => [ 'title' => m_('Database Tests'), 'sort_order' => 20, 'class' => DbTestSuite::class,
        'modules' => [],
    ],
    'app_tests' => [ 'title' => m_('Application Tests'), 'sort_order' => 30],
    'doc_tests' => [ 'title' => m_('Documentation Tests'), 'sort_order' => 40],
];
